<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;

class User extends Model
{
    protected static $table;
    //protected static $primaryKey = 'id';
    //protected static $incrementing = true;
    //protected static $alreadyLaunched = false;

    /**
     * Create an instance.
     */
    public function __construct($attributes = [])
    {
        // tell model already launched
        static::$alreadyLaunched = true;

        // assign table name by class name with plural
        $this->assignTableName();

        // you can override some attributes from the Model class via SETs
        //static::setTable('tablename');
    }
}
