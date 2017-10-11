<?php

namespace Config\Core;

class Config
{
    private static $_data = [];

    public function __construct()
    {
        // load global settings
        foreach (scandir('../config') as $filename) {
            $path = '../config/' . $filename;
            if (is_file($path)) {
                self::$_data[explode('.', $filename)[0]] = require_once $path;
            }
        }
    }

    /**
     * Easily call parameters from config array f.e.: Config::get('database.mysql.host')
     *
     * @param string $path      path in the setting array, f.e.: Config::get('database.mysql.host')
     * @return string           setting value if exists, returns null if not
     */
    public static function get($path = null)
    {
        if ($path) {
            $config = self::$_data;
            $path = explode('.', $path);
                
            foreach ($path as $bit) {
                if (isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }
                
            return $config;
        }
            
        return null;
    }
}
