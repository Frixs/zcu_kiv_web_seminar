<?php

namespace Frixs\Language;

use Frixs\Config\Config;

class Lang
{
    private static $_data = [];

    /**
     * Initialize the Lang. Load all data.
     *
     * @return void
     */
    public static function init($dirPath = '')
    {
        $langPath = '../resources/lang/'. Config::get('app.locale') . $dirPath;
        
        // require all language files
        foreach (scandir($langPath) as $filename) {
            // filter link to parent subfolders
            if ($filename[0] === '.') {
                continue;
            }

            $path = $langPath .'/'. $filename;
            if (is_file($path)) {
                // if there is no subfolder, you can require the file easily
                if ($dirPath == null) {
                    self::$_data[explode('.', $filename)[0]] = require_once $path;
                    continue;
                }

                // if there is a subfolder...
                $keys      = explode('/', substr($dirPath, 1));
                $reference = &self::$_data; // reference with address to the data array

                // go through the array to specific path where the file has to be saved
                foreach ($keys as $key) {
                    if (!array_key_exists($key, $reference)) {
                        $reference[$key] = [];
                    }

                    $reference = &$reference[$key];
                }
                // require data file into the correct array with its key
                $reference[explode('.', $filename)[0]] = require_once $path;
            } elseif (is_dir($path)) {
                // if it is dir, go to recursive function to require data from subfolders.
                $subdir = explode('/', $path);
                $subdir = end($subdir); // get the last item of the path
                $subdir = ($dirPath == null) ? $subdir : $dirPath .'/'. $subdir; // join the last item of the path with previous subfolders if exists
                self::init('/'. $subdir);
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
