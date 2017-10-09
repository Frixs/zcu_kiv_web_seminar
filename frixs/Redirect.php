<?php

//TODO move to Router
namespace Frixs;

class Redirect
{
    /**
     * Redirect to the current page
     *
     * @param string $location      path location or integer error page
     * @return void
     */
    public static function to($location = null)
    {
        if ($location) {
            if (is_numeric($location)) {
                switch ($location) {
                    case 001: // Maintenance
                        header('Location: ../error/001', true, 301);
                        exit();
                        break;
                    case 404:
                        header('HTTP/1.1 404 Not Found');
                        header('Location: ../error/404', true, 301);
                        exit();
                        break;
                    case 501:
                        header('Location: ../error/501', true, 301);
                        exit();
                        break;
                    case 503:
                        header('Location: ../error/503', true, 301);
                        exit();
                        break;
                }
            }

            header('Location: '. $location, true, 301);
            exit();
        }
    }
}
