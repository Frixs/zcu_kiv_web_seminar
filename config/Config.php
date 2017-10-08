<?php

class Config
{
    public function __construct()
    {
        // initialize global setting array
        $GLOBALS['config_app'] = [];

        // load global settings
        foreach (scandir('../config/settings') as $filename) {
            $path = '../config/settings/' . $filename;
            if (is_file($path)) {
                require_once $path;
            }
        }

        // load setting classes
        foreach (scandir('../config/classes') as $filename) {
            $path = '../config/classes/' . $filename;
            if (is_file($path)) {
                require_once $path;
            }
        }
    }

    /**
     * Easily call parametres from $GLOBALS_WEB array f.e.: Config::get('database.mysql.host')
     *
     * @param string $path      path in the setting array, f.e.: Config::get('database.mysql.host')
     * @return string           setting value if exists, returns null if not
     */
    public static function get($path = null)
    {
        if ($path) {
            $config = $GLOBALS['config_app'];
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
