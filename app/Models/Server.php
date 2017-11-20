<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;

use Frixs\Routing\Router;

class Server extends Model
{
    // Use Model parameters.
    use \Frixs\Database\Eloquent\ModelParameters;

    protected static $server = null;

    public function __construct($attributes = [])
    {
        $this->initModel();

        // You can override attributes from the Model class via SETs.
        //static::setTable('TABLE_NAME');
    }

    /**
     * Get attribute
     *
     * @return integer or null
     */
    public static function getServerID()
    {
        return self::$server;
    }

    /**
     * Set attribute
     *
     * @param integer $id
     * @return void
     */
    public static function setServerID($id)
    {
        self::$server = $id;
    }

    /**
     * Get Top Servers.
     *
     * @return void
     */
    public static function getTopServers()
    {
        $query = self::db()->query(
            "SELECT s.id,
                s.name,
                s.is_private,
                s.has_background_box,
                (
                    SELECT COUNT(DISTINCT ug.user_id)
                    FROM ". UserGroup::getTable() ." AS ug
                    WHERE ug.server_id = s.id
                ) AS user_count
            FROM ". self::getTable() ." AS s
            WHERE 1
            ORDER BY user_count DESC"
        , [
        ]);

        if ($query->error()) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }
        
        return $query->get();
    }
}
