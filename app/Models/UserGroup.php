<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;

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
}