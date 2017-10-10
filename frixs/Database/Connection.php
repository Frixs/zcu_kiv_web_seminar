<?php

namespace Frixs\Database;

use Config\Core\Config;

/**
 * Singleton
 */
class Connection
{
    private static $_instance   = null;
    private $connectionInstance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new Connection();
        }

        if (self::$_instance->connectionInstance != null) {
            return self::$_instance->connectionInstance;
        }

        switch (Config::get("database.connection_type")) {
            case 'mysql':
                self::$_instance->connectionInstance = MysqlConnection::getInstance();
                break;
        }

        return self::$_instance->connectionInstance;
    }
}
