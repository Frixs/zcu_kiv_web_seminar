<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;

class User extends Model
{
    /**
     * Create an instance.
     */
    public function __construct($attributes = [])
    {
        // tell model already launched
        self::$alreadyLaunched = true;

        // assign table name by class name with plural
        $this->assignTableName();

        // you can override some attributes from the Model class via SETs
        //self::setTable('users');
    }
}
