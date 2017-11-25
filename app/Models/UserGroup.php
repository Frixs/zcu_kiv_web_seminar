<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;

use Frixs\Routing\Router;

class UserGroup extends Model
{
    // Use Model parameters.
    use \Frixs\Database\Eloquent\ModelParameters;

    public function __construct($attributes = [])
    {
        $this->initModel();

        // You can override attributes from the Model class via SETs.
        static::setPrimaryKey(null);
        static::setIncrementing(false);
    }

    /**
     * Get User's Servers.
     *
     * @param integer $uid
     * @return void
     */
    public static function getUserServers($uid)
    {
        if ($uid <= 1) { // ANONYMOUS
            self::db()->rollBack();
            Router::redirectToError(501, \get_called_class());
        }

        $query = self::db()->query(
            "SELECT DISTINCT ug.server_id,
                s.name,
                s.access_type,
                s.has_background_placeholder,
                (
                    SELECT COUNT(DISTINCT ug.user_id)
                    FROM ". self::getTable() ." AS ug
                    WHERE ug.user_id = ? AND ug.server_id > ?
                ) AS user_count
            FROM ". self::getTable() ." AS ug
            INNER JOIN ". Server::getTable() ." AS s
                ON s.". Server::getPrimaryKey() ." = ug.server_id
            WHERE ug.user_id = ? AND ug.server_id > ?
            ORDER BY s.name ASC"
        , [
            $uid,
            0,
            $uid,
            0
        ]);

        if ($query->error()) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }
        
        return $query->get();
    }
}
