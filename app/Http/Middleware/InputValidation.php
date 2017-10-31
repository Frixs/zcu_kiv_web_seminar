<?php

namespace App\Http\Middleware;

use Frixs\Http\Input;
use Frixs\Validation\Escape;

class InputValidation
{
    public static function validate($parameters = [])
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
            foreach (Input::all($type) as $key => $value) {
                // Input value validation
                Input::set($type, $key, Escape::input($value));
            }
        }
    }
}
