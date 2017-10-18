<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;

class Server extends Model
{
    // Use Model parameters.
    use \Frixs\Database\Eloquent\ModelParameters;

    public function __construct($attributes = [])
    {
        $this->initModel();

        // You can override attributes from the Model class via SETs.
        //static::setTable('TABLE_NAME');
    }
}