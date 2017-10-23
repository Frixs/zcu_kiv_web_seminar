<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;
use Frixs\Routing\Router;
use Frixs\Language\Lang;

class Group extends Model
{
    // Use Model parameters.
    use \Frixs\Database\Eloquent\ModelParameters;

    private static $_data = [];

    public function __construct($attributes = [])
    {
        $this->initModel();

        // You can override attributes from the Model class via SETs.
        //static::setTable('TABLE_NAME');
    }

    public static function loadGroupData()
    {
        $query = self::db()->selectAll(self::getTable(), [1]);
        if ($query->error()) {
            Router::redirectToError(501);
        }

        $id = self::getPrimaryKey();
        foreach ($query->get() as $item) {
            self::$_data[$item->$id] = [
                'name'  => $item->name,
                'color' => $item->color
            ];
        }
    }

    /**
     * Get ID of the current group.
     *
     * @return integer
     */
    public static function Admin()
    {
        return 1;
    }

    /**
     * Get ID of the current group.
     *
     * @return integer
     */
    public static function Moderator()
    {
        return 2;
    }

    /**
     * Get ID of the current group.
     *
     * @return integer
     */
    public static function SMaster()
    {
        return 3;
    }

    /**
     * Get ID of the current group.
     *
     * @return integer
     */
    public static function SMember()
    {
        return 4;
    }

    /**
     * Get ID of the current group.
     *
     * @return integer
     */
    public static function SRecruit()
    {
        return 5;
    }
}
