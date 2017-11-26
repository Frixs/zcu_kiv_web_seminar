<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;

use Frixs\Routing\Router;
use Frixs\Http\Input;

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
        if (!self::$server) {
            if (Input::get('serverid')) {
                self::setServerID(Input::get('serverid'));
            }
        }

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
     * Get server data by its ID.
     *
     * @param integer $serverID
     * @return object
     */
    public static function getServer($serverID)
    {
        if (!$serverID) {
            Router::redirectToError(501, 'Server::getServer();');
        }

        $query = self::db()->selectAll(self::getTable(), [
            self::getPrimaryKey(), '=', $serverID
        ], [], 1);

        if ($query->error() || !$query->count()) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        return $query->getFirst();
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
                s.access_type,
                s.has_background_placeholder,
                (
                    SELECT COUNT(DISTINCT ug.user_id)
                    FROM ". UserGroup::getTable() ." AS ug
                    WHERE ug.server_id = s.id
                ) AS user_count
            FROM ". self::getTable() ." AS s
            WHERE 1
            ORDER BY user_count DESC, s.name ASC
            LIMIT 3"
        , [
        ]);

        if ($query->error()) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }
        
        return $query->get();
    }
}
