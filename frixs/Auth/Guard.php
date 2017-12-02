<?php

namespace Frixs\Auth;

use Frixs\Routing\Router;
use Frixs\Auth\Auth;

use App\Models\Group;

class Guard
{
    /** Singleton instance. */
    private static $_instance = null;
    /** Guard data. */
    private $_data;

    /**
     * Constructor.
     */
    private function __construct($data) {
        if (!$data) {
            Router::redirectToError(501);
        }

        $this->_data = $data;
    }

    /**
     * Singleton model object, get instance of it
     *
     * @return object   Database instance
     */
    public static function getInstance($data = null)
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Guard($data);
        }
        return self::$_instance;
    }

    /**
     * Easily call parameters from guard array f.e.: Guard::get('server.invite')
     *
     * @param string $path      path in the setting array, f.e.: Guard::get('server.invite')
     * @return string           setting value if exists, returns null if not
     */
    protected function get($path = null)
    {
        if (!$path) {
            return null;
        }

        $config = $this->_data;
        $path = explode('.', $path);
            
        foreach ($path as $bit) {
            if (isset($config[$bit])) {
                $config = $config[$bit];
            }
        }
        
        return $config;
    }

    /**
     * Check if user has acces to the papth's section.
     *
     * @param string $path      path in the setting array, f.e.: Guard::get('server.invite')
     * @return boolean
     */
    protected function has($path = null)
    {
        $perms = $this->get($path);

        if (!$perms) {
            return null;
        }
        
        for ($i = 0; $i < count($perms); $i++) {
            if (isset($perms[$i])) {
                if (is_bool($perms[$i]) && $perms[$i]) {
                    return true;
                } else if (Auth::guard($perms[$i])) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Call static to easily access get method.
     *
     * @param string $name
     * @param mixed $arguments
     * @return void
     */
    public static function __callStatic($name, $arguments)
    {
        switch ($name) {
            case 'has':
                return self::getInstance()->$name($arguments[0]);
                break;
            default:
                Router::redirectToError(501, "Guard::__call();");
        }
    }
}