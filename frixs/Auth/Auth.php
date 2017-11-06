<?php

namespace Frixs\Auth;

use App\Models\User;
use App\Models\Session as SessionModel;
use Frixs\Config\Config;
use Frixs\Routing\Router;
use Frixs\Cookie\Cookie;
use Frixs\Session\Session;

use App\Models\UserGroup;
use App\Models\Group;

/**
 * Parent is general User and his parent is general Model
 */
class Auth extends User
{
    /**
     * Get all data of the current logged user
     *
     * @return object
     */
    public static function user()
    {
        $query = self::db()->selectAll(User::getTable(), [User::getPrimaryKey(), '=', self::id()], [], 1);
        return $query->getFirst();
    }

    /**
     * Get ID of the current logged user
     *
     * @return integer
     */
    public static function id()
    {
        $sessionId = '';
        $remember;

        if (Cookie::exists(Config::get('auth.session_name'))) {
            $sessionId = Cookie::get(Config::get('auth.session_name'));
            $remember = 1;
        } elseif (Session::exists(Config::get('auth.session_name'))) {
            $sessionId = Session::get(Config::get('auth.session_name'));
            $remember = 0;
        } else {
            return 1;
        }

        $query = self::db()->select(SessionModel::getTable(), [
            SessionModel::getPrimaryKey(), '=', $sessionId,
            'AND', 'ip', '=', getClientIP(),
            'AND', 'browser', '=', getClientBrowserInfo(),
            'AND', 'remember', '=', $remember
        ], ['user_id'], [], 1);

        if ($query->count()) {
            return $query->getFirst()->user_id;
        }

        return 1;
    }

    /**
     * Check if the current user is logged in.
     * If not, user's sessions will be deleted if exists
     *
     * @return bool
     */
    public static function check()
    {
        if (self::id() === 1) {
            // if user has been forced logout, delete his sessions
            Session::delete(Config::get('auth.session_name'));
            Cookie::delete(Config::get('auth.session_name'));

            return false;
        }

        return true;
    }

    /**
     * Verify and login the user
     *
     * @param [type] $email
     * @param [type] $password
     * @param bool $remember
     * @return bool         success of login
     */
    public static function login($email, $password, $remember = false)
    {
        $uid = self::verify($password, ['email' => $email]);

        if (!$uid) {
            return false;
        }

        $sessionId = md5(uniqid($uid, true)) . md5(uniqid(date('Y-m-d H:i:s'), true));

        $query = self::db()->insert(SessionModel::getTable(), [
            SessionModel::getPrimaryKey() => $sessionId,
            'user_id' => $uid,
            'ip' => getClientIP(),
            'browser' => getClientBrowserInfo(),
            'session_start' => time(),
            'remember'=> $remember ? 1 : 0
        ]);

        if (!$query) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        if ($remember) {
            Cookie::put(Config::get('auth.session_name'), $sessionId, time() + Config::get('auth.remember_expiration'));
        } else {
            Session::put(Config::get('auth.session_name'), $sessionId);
        }

        return true;
    }

    /**
     * Check user credentials with the database. If exists grab user's ID.
     *
     * @param string $password
     * @param array $attributes
     * @return integer or null      user ID
     */
    public static function verify($password, $attributes = [])
    {
        $whereCond = [];
        $primaryKey = User::getPrimaryKey();

        $i = 0;
        foreach ($attributes as $key => $value) {
            if ($i > 0) {
                $whereCond[] = 'AND';
            }

            $whereCond[] = $key;
            $whereCond[] = '=';
            $whereCond[] = $value;

            $i++;
        }

        $query = self::db()->select(User::getTable(), $whereCond, [$primaryKey, 'password', 'form_salt'], [], 1);
        if ($query->error()) {
            Router::redirectToError(500);
        }

        if ($query->count() && self::verifyPassword($password . $query->getFirst()->form_salt, $query->getFirst()->password)) {
            return $query->getFirst()->$primaryKey;
        }

        return null;
    }

    /**
     * Logout the current logged user.
     * Or you can logout any other user if you will setup $uid parameter.
     *
     * @param integer $uid
     * @return void
     */
    public static function logout($uid = 0)
    {
        if ($uid > 0) {
            self::db()->delete(SessionModel::getTable(), ['user_id', '=', $uid]);
            return;
        }

        $sessionId = '';

        if (Cookie::exists(Config::get('auth.session_name'))) {
            $sessionId = Cookie::get(Config::get('auth.session_name'));
        } elseif (Session::exists(Config::get('auth.session_name'))) {
            $sessionId = Session::get(Config::get('auth.session_name'));
        }

        self::db()->delete(SessionModel::getTable(), [SessionModel::getPrimaryKey(), '=', $sessionId]);
    }

    /**
     * Check user's role/permissions
     *
     * @param string $roleID
     * @return bool
     */
    public static function guard($roleID)
    {
        $query = self::db()->query("
                SELECT g.server_group
                FROM ". UserGroup::getTable() ." AS ug
                INNER JOIN ". Group::getTable() ." AS g
                    ON ug.group_id = ". Group::getPrimaryKey() ."
                WHERE ug.user_id = ? AND ug.group_id = ? AND (g.server_group = ? OR (g.server_group = ? AND ug.server_id = ?))
            ", [
                Auth::id(),
                $roleID,
                0,
                1,
                \App\Models\Server::getServerID()
            ]);

        if ($query->error()) {
            Router::redirectToError(500);
        }
    
        if (!$query->count()) {
            return true;
        }

        return false;
    }

    /**
     * Hash a password to the correct form.
     *
     * @param string $password
     * @param string $salt
     * @return string               Hashed password
     */
    public static function hashPassword($password, $salt)
    {
        $options = [
            'cost' => 12,
        ];
        
        return password_hash($password . $salt, PASSWORD_BCRYPT, $options);
    }

    /**
     * Verify password
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Generate salt.
     *
     * @return string
     */
    public static function generateSalt() {
        return md5(uniqid(mt_rand(), true));
    }
}
