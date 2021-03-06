<?php

namespace App\Http\Middleware;

use Frixs\Routing\Router;
use Frixs\Auth\Auth;

class GuardAll extends \Frixs\Http\Middleware
{
    public static function validate($parameters = [])
    {
        for ($i = 0; $i < count($parameters); $i++) {
            if (is_bool($parameters[$i])) {
                if (!$parameters[$i]) {
                    Router::redirectToError(401);
                }
                continue;
            }

            if (!Auth::guard($parameters[$i])) {
                Router::redirectToError(401);
            }
        }
        
        return true;
    }
}
