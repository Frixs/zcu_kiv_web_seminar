<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;

class CalendarEventUser extends Model
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
        static::setPrimaryKey(null);
        static::setIncrementing(false);
    }
}
