<?php

namespace App\Http\Middleware;

use Frixs\Routing\Router;
use Frixs\Auth\Auth;

class GuardAny extends \Frixs\Http\Middleware
{
    public static function validate($parameters = [])
    {
        for ($i = 0; $i < count($parameters); $i++) {
            if (Auth::guard($parameters[$i])) {
                return true;
            }
        }
        
        Router::redirectToError(401);
    }
}
