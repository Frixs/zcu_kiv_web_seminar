<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected static $table;
    protected static $alreadyLaunched = false;
    protected static $primaryKey = null;
    protected static $incrementing = false;
    //... another attributes

    public function __construct($attributes = [])
    {
        // tell that model is already launched
        static::$alreadyLaunched = true;

        // assign table name by class name with plural
        $this->assignTableName();

        // you can override some attributes from the Model class via SETs
        //static::setTable('tablename');
    }
}