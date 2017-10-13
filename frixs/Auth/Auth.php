<?php

namespace Frixs\Auth;

use App\Models\User;
use Frixs\Routing\Router;

// TODO complete this class after creating the database structure
class Auth extends User
{
    /**
     * Get all data of the current logged user
     *
     * @return object
     */
    public static function user()
    {
    }

    /**
     * Get ID of the current logged user
     *
     * @return integer
     */
    public static function id()
    {
    }

    /**
     * Check if the current user is logged in.
     *
     * @return bool
     */
    public static function check()
    {
    }

    /**
     * Check user credentials with the database. If exists grab user's ID.
     *
     * @param array $attributes
     * @return integer              id of the user
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

        if($query->count()){
            return $query->getFirst()->$primaryKey;
        }

        return 0;
    }

    /**
     * Login user by the ID
     *
     * @param integer $id
     * @param bool $remember
     * @return void
     */
    public static function login($id, $remember = false)
    {
    }

    /**
     * Logout the current logged user.
     *
     * @return void
     */
    public static function logout()
    {
    }

    /**
     * Check user's role/permissions
     *
     * @param string $role
     * @return bool
     */
    public static function guard($role)
    {
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
