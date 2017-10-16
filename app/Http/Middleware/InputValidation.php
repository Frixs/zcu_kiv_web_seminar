<?php

namespace App\Http\Middleware;

use Frixs\Http\Input;

class InputValidation
{
    public static function validate()
    {
        self::checkValues('get');
        self::checkValues('post');

        return true;
    }

    /**
     * Validate all values of the input array
     *
     * @param string $type
     * @return void
     */
    protected static function checkValues($type)
    {
        if (Input::exists($type)) {
            foreach (Input::getAll($type) as $key => $value) {
                // Input value validation
                trim($value);
            }
        }
    }
}
