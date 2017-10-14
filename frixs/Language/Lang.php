<?php

namespace Frixs\Language;

use Frixs\Config\Config;

class Lang
{
    private static $_data = [];

    public function __construct()
    {
        $langpath = '../resources/lang/'. Config::get('app.locale');
        
        // load language
        foreach (scandir($langpath) as $filename) {
            $path = $langpath .'/'. $filename;
            if (is_file($path)) {
                self::$_data[explode('.', $filename)[0]] = require_once $path;
            }
        }
    }

    /**
     * Easily call parametres from language array f.e.: Lang::get('auth.failed')
     *
     * @param string $path          path in the lang array, f.e.: Lang::get('auth.failed')
     * @param array $parameters     parameters f.e. ['key' => 'value'] replace mark in the text like :key
     * @return string               lang value if exists, returns empty string if not
     */
    public static function get($path, $parameters = [])
    {
        $config = self::$_data;
        $path = explode('.', $path);
                
        foreach ($path as $bit) {
            if (isset($config[$bit])) {
                $config = $config[$bit];
            }
        }
        
        if (is_array($config)) {
            return "";
        }

        // replace anchors for parameters if exists
        if (count($parameters) > 0) {
            foreach ($parameters as $key => $value) {
                $config = str_replace(':'. $key, $value, $config);
            }
        }

        return $config;
    }
}
