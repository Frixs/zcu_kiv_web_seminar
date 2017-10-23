<?php

namespace Frixs\Routing;

use Frixs\Config\Config;
use Frixs\Session\Session;

class Router
{
    /**
     * Redirect to a page
     *
     * @param string $path
     * @param bool $includeAppRoot      Redirect without App root path prefix to be able to enter whole URL, f.e. directed to foreign websize.
     * @param integer $status
     * @return void
     */
    public static function redirectTo($path, $includeAppRoot = true, $status = 301)
    {
        if (!is_string($path)) {
            self::redirectToError(500);
        }

        if ($status == 301) {
            header('HTTP/1.1 301 Moved Permanently');
        }

        if ($includeAppRoot) {
            $path = Config::get('app.root') .'/'. $path;
        }

        header('Location: '. $path, true, $status);
        exit();
    }

    /**
     * Redirect to error page
     *
     * @param integer $errorCode    http error code
     * @param string $message       messagage which can be passed through into the error page
     * @return void
     */
    public static function redirectToError($errorCode = 500, $message = 'null')
    {
        if (!empty($message)) {
            Session::flash('error_message', $message);
        }

        switch ($errorCode) {
            case 001: // Maintenance
                header('Location: '. Config::get('app.root') .'/error/001', true, 301);
                exit();
                break;
            case 404:
                header('HTTP/1.1 404 Not Found');
                header('Location: '. Config::get('app.root') .'/error/404', true, 301);
                exit();
                break;
            case 500:
                header('Location: '. Config::get('app.root') .'/error/500', true, 301);
                exit();
                break;
            case 501:
                header('Location: '. Config::get('app.root') .'/error/501', true, 301);
                exit();
                break;
            case 503:
                header('Location: '. Config::get('app.root') .'/error/503', true, 301);
                exit();
                break;
            default:
                if (Session::exists('error_message')) {
                    Session::flash('error_message');
                }
        }
    }
}
