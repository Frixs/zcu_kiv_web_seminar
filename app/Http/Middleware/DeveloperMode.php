<?php

namespace App\Http\Middleware;

use Frixs\Routing\Router;
use Frixs\Config\Config;

class DeveloperMode
{
    public static function validate()
    {
        if (Config::get('app.developer_mode')) {
            // turn off cache
            opcache_reset();
            clearstatcache();
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
        }

        return true;
    }
}
