<?php

namespace Frixs\Auth;

use App\Models\User;
use App\Models\Session as MSession;
use Frixs\Config\Config;
use Frixs\Routing\Router;
use Frixs\Cookie\Cookie;
use Frixs\Session\Session;

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
     * @return integer or null
     */
    public static function id()
    {
        $sessionId = '';
        $remember;

        if (Cookie::exists(Config::get('auth.session_name'))) {
            $sessionId = Cookie::get(Config::get('auth.session_name'));
            $remember = true;
        } elseif (Session::exists(Config::get('auth.session_name'))) {
            $sessionId = Session::get(Config::get('auth.session_name'));
            $remember = false;
        } else {
            return null;
        }

        $query = self::db()->select(MSession::getTable(), [
            Msession::getPrimaryKey(), '=', $sessionId,
            'AND', 'ip', '=', getClientIP(),
            'AND', 'browser', '=', getClientBrowserInfo(),
            'AND', 'remember', '=', $remember
        ], ['user_id'], [], 1);

        if ($query->count()) {
            return $query->getFirst()->user_id;
        }

        return null;
    }

    /**
     * Check if the current user is logged in.
     * If not, user's sessions will be deleted if exists
     *
     * @return bool
     */
    public static function check()
    {
        if (self::id() === null) {
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
        $uid = self::verify($email, $password);

        if (!$uid) {
            return false;
        }

        $sessionId = md5(uniqid($uid, true)) . md5(uniqid(date('Y-m-d H:i:s'), true));

        $query = self::db()->insert(User::getTable(), [
            Msession::getPrimaryKey() => $sessionId,
            'user_id' => $uid,
            'ip' => getClientIP(),
            'browser' => getClientBrowserInfo(),
            'session_start' => time(),
            'remember'=> $remember
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
     * @return void
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

        if (self::verifyPassword($password . $query->getFirst()->form_salt, $query->getFirst()->password)) {
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
            self::db()->delete(MSession::getTable(), ['user_id', '=', $uid]);
            return;
        }

        $sessionId = '';

        if (Cookie::exists(Config::get('auth.session_name'))) {
            $sessionId = Cookie::get(Config::get('auth.session_name'));
        } elseif (Session::exists(Config::get('auth.session_name'))) {
            $sessionId = Session::get(Config::get('auth.session_name'));
        }

        self::db()->delete(MSession::getTable(), [Msession::getPrimaryKey(), '=', $sessionId]);
    }

    /**
     * Check user's role/permissions
     *
     * @param string $role
     * @return bool
     */
    public static function guard($role)
    {
        //TODO
    }

    /**
     * Hash a password to the correct form.
     *
     * @param string $password
     * @return string               Hashed password
     */
    public static function hashPassword($password)
    {
        $options = [
            'cost' => 12,
        ];

        return password_hash($password . self::user()->form_salt, PASSWORD_BCRYPT, $options);
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
}
