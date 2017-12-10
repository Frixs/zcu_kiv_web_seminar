<?php

namespace App\Http\Middleware;

use Frixs\Http\Input;
use Frixs\Validation\Escape;

class InputValidation extends \Frixs\Http\Middleware
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
                // If input is an array.
                if (is_array($value)) {
                    $tempArr = [];
                    foreach ($value as $valKey => $valValue) {
                        $tempArr[$valKey] = Escape::input($value[$valKey]);
                    }
                    // Input value validation
                    Input::set($type, $key, $tempArr);
                    continue;
                }

                // Input value validation
                Input::set($type, $key, Escape::input($value));
            }
        }
    }
}
