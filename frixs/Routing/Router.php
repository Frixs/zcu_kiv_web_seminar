<?php

namespace Frixs\Routing;

class Router
{
    public function __construct()
    {
    }

    public static function redirectTo($path, $status = 301)
    {
        if ($status == 301) {
            header('HTTP/1.1 301 Moved Permanently');
        }

        header('Location: '. $path, true, $status);
        exit();
    }

    public static function redirectToError($errorCode = 500)
    {
        switch ($errorCode) {
            case 001: // Maintenance
                header('Location: error/001', true, 301);
                exit();
                break;
            case 404:
                header('HTTP/1.1 404 Not Found');
                header('Location: error/404', true, 301);
                exit();
                break;
            case 500:
                header('Location: error/500', true, 301);
                exit();
                break;
            case 501:
                header('Location: error/501', true, 301);
                exit();
                break;
            case 503:
                header('Location: error/503', true, 301);
                exit();
                break;
        }
    }
}
