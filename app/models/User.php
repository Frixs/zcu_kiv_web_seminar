<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;

class User extends Model
{
    // Use Model parameters.
    use \Frixs\Database\Eloquent\ModelParameters;

    /**
     * Create an instance of the model.
     */
    public function __construct($attributes = [])
    {
        $this->initModel();

        // You can override attributes from the Model class via SETs.
        //static::setTable('TABLE_NAME');
    }

    /**
     * Get user data by ID
     *
     * @param integer $uid
     * @return object
     */
    public static function getUser($uid)
    {
        $query = self::db()->selectAll(self::getTable(), [self::getPrimaryKey(), '=', $uid], [], 1);
        return $query->getFirst();
    }
}
