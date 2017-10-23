<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;

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
    public static function getServer()
    {
        return self::$server;
    }

    /**
     * Set attribute
     *
     * @param integer $id
     * @return void
     */
    public static function setServer($id)
    {
        self::$server = $id;
    }
}
