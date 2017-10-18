<?php

/**
 * There you can define global functions.
 * It is very useful f.e. if you need to call some Lang or Confing in your views. You dont need to define 'use' path.
 */

use Frixs\Config\Config;
use Frixs\Language\Lang;
use Frixs\Routing\Router;

function instance($classname, $parameters = [])
{
    if (!array_key_exists($classname, Config::get('app.aliases'))) {
        Router::redirectToError(501, Lang::get('error.failed_to_recognize_alias', ['alias' => $classname]));
    }

    $class = Config::get('app.aliases')[$classname];

    return new $class(implode(', ', $parameters));
}

/**
 * Function to get the client IP address
 *
 * @return string
 */
function getClientIP()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }
        
    return $ipaddress;
}

/**
 * Function to get client browser info
 *
 * @return string
 */
function getClientBrowserInfo()
{
    return $_SERVER['HTTP_USER_AGENT'];
}
