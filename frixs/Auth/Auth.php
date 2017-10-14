<?php

namespace Frixs\Auth;

use App\Models\User;
use App\Models\Session as MSession;
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
        $query = self::db()->selectAll(self::getTable(), ['id', '=', self::id()], [], 1);
        return $query->get();
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
            $remember = true;
        } elseif (Session::exists(Config::get('auth.session_name'))) {
            $sessionId = Session::get(Config::get('auth.session_name'));
            $remember = false;
        } else {
            return 0;
        }

        $query = self::db()->select(MSession::getTable(), [
            'id', '=', $sessionId,
            'AND', 'ip', '=', getClientIP(),
            'AND', 'browser', '=', getClientBrowserInfo(),
            'AND', 'remember', '=', $remember
        ], ['user_id'], [], 1);

        if ($query->count()) {
            return $query->getFirst()->user_id;
        }

        return 0;
    }

    /**
     * Check if the current user is logged in.
     *
     * @return bool
     */
    public static function check()
    {
        if (self::id() > 0) {
            return true;
        }

        // if user has been forced logout, delete his sessions
        Session::delete(Config::get('auth.session_name'));
        Cookie::delete(Config::get('auth.session_name'));

        return false;
    }

    /**
     * Check user credentials with the database. If exists grab user's ID.
     *
     * @param array $attributes
     * @return integer              id of the user if exists or 0
     */
    public static function attempt($attributes = [])
    {
        $whereCond = [];
        $primaryKey = self::getPrimaryKey();

        foreach ($attributes as $key => $value) {
            $whereCond[] = $key;
            $whereCond[] = '=';
            $whereCond[] = $value;
        }

        $query = self::db()->select(self::getTable(), $whereCond, [$primaryKey], [], 1);
        if ($query->error()) {
            Router::redirectToError(500);
        }

        if ($query->count()) {
            return $query->getFirst()->$primaryKey;
        }

        return 0;
    }

    /**
     * Login user by the ID
     *
     * @param integer $uid
     * @param bool $remember
     * @return void
     */
    public static function login($uid, $remember = false)
    {
        $sessionId = md5(uniqid($uid, true)) . md5(uniqid(date('Y-m-d H:i:s'), true));

        $query = self::db()->insert(self::getTable(), [
            'id' => $sessionId,
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

        self::db()->delete(MSession::getTable(), ['id', '=', $sessionId]);
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
     * @param integer $uid
     * @param string $password
     * @return string               Hashed password
     */
    protected static function hashPassword($uid, $password)
    {
    }
}
