<?php

/**
 * There you can define global functions.
 * It is very useful f.e. if you need to call some Lang or Confing in your views. You dont need to define 'use' path.
 */

use Frixs\Language\Lang;

/**
 * Get language string
 *
 * @param string $path
 * @param array $parameters
 * @return string
 */
function getLang($path, $parameters = [])
{
    return Lang::get($path, $parameters);
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
