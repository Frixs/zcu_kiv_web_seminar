<?php

namespace Frixs\Auth;

trait UserTrait
{
    /**
     * Get user data by ID
     *
     * ---
     * Use: User::get(<ID>)->username;
     * ---
     *
     * @param integer $uid
     * @return object
     */
    public static function get($uid)
    {
        $query = self::db()->selectAll(self::getTable(), [self::getPrimaryKey(), '=', $uid], [], 1);

        if ($query->error()) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        return $query->getFirst();
    }
}