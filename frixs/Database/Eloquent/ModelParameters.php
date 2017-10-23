<?php

namespace Frixs\Database\Eloquent;

use Frixs\Config\Config;

trait ModelParameters
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected static $table;
    
    /**
     * Indicates if the model is launched, if the model already has set table name and another attributes.
     *
     * @var bool
     */
    protected static $alreadyLaunched = false;
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected static $primaryKey = 'id';
    
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    protected static $incrementing = true;

    /**
     * This method is called in model's constructors to initialize them.
     *
     * @return void
     */
    protected function initModel()
    {
        // Tell, model already launched.
        static::$alreadyLaunched = true;
        
        // Assign table name by class name with plural.
        $this->assignTableName();
    }

    /**
     * Returns table name according to class name with plural
     *
     * @return void
     */
    protected function assignTableName()
    {
        $classNameParts = explode('\\', get_class($this));
        $parts = preg_split("/(?=[A-Z])/", lcfirst(end($classNameParts)));
        $tablename = Config::get('database.app_table_prefix') . strtolower(implode('_', $parts)) .'s';
        static::setTable($tablename);
    }

    /**
     * Get attribute
     *
     * @return string
     */
    protected static function getTable()
    {
        if (!static::isAlreadyLaunched()) {
            new static();
        }

        return static::$table;
    }

    /**
     * Set attribute
     *
     * @param string $table
     * @return void
     */
    protected static function setTable($table)
    {
        static::$table = $table;
    }

    /**
     * Get attribute
     *
     * @return string or null if table is M:N
     */
    protected static function getPrimaryKey()
    {
        if (!static::isAlreadyLaunched()) {
            new static();
        }

        return static::$primaryKey;
    }

    /**
     * Set attribute
     *
     * @param string $primaryKey
     * @return void
     */
    protected static function setPrimaryKey($primaryKey)
    {
        static::$primaryKey = $primaryKey;
    }

    /**
     * Get attribute
     *
     * @return bool
     */
    protected static function getIncrementing()
    {
        if (!static::isAlreadyLaunched()) {
            new static();
        }

        return static::$incrementing;
    }

    /**
     * Set attribute
     *
     * @param bool $incrementing
     * @return void
     */
    protected static function setIncrementing($incrementing)
    {
        static::$incrementing = $incrementing;
    }

    /**
     * Get attribute
     *
     * @return bool
     */
    protected static function isAlreadyLaunched()
    {
        return static::$alreadyLaunched;
    }
}
