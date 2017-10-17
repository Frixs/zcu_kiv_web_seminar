<?php

namespace Frixs\Database\Eloquent;

use Frixs\Database\Connection as DB;
use Frixs\Routing\Router;
use Frixs\Config\Config;

/**
 |  Default structure for your models
 |  ==============================================
 |  You dont need to declare all of the attributes
 |  if you dont need them.
 |  But the attributes without default value must
 |  be declared in your models.
 |________________________________________________

namespace App\Models;

use Frixs\Database\Eloquent\Model;

class <CLASSNAME> extends Model
{
    protected static $table;
    protected static $alreadyLaunched = false;
    //protected static $primaryKey = 'id';
    //protected static $incrementing = true;
    //... another attributes

    public function __construct($attributes = [])
    {
        // tell that model is already launched
        static::$alreadyLaunched = true;

        // assign table name by class name with plural
        $this->assignTableName();

        // you can override some attributes from the Model class via SETs
        //static::setTable('tablename');
    }
}

*/
abstract class Model
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

    /*
     |   *************
     |  *** METHODS ***
     |   *************
     */

    /**
     * Return connection instance to execute query
     *
     * @return mixed
     */
    protected static function db()
    {
        return DB::getInstance();
    }

    /**
     * Get record from a database by ID
     *
     * @param integer $id
     * @return object
     */
    public static function find($id)
    {
        return self::db()->select(static::getTable(), [static::getPrimaryKey(), '=', $id], ['*'], [], 1);
    }
    
    /**
     * Get all records from a database
     *
     * @param array $orderBy
     * @param integer $limit
     * @return object
     */
    public static function all($orderBy = [], $limit = 0)
    {
        return self::db()->select(static::getTable(), ['1'], ['*'], $orderBy, $limit);
    }

    /**
     * Delete records from a database
     *
     * @param array $id
     * @return void
     */
    public static function destroy($id = [])
    {
        for ($i = 0; $i < count($id); $i++) {
            $query = self::db()->delete(static::getTable(), [static::getPrimaryKey(), '=', $id]);
            if ($query->error()) {
                self::db()->rollBack();
                Router::redirectToError(500);
            }
        }
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
    public static function getTable()
    {
        if (!static::isAlreadyLaunched()) {
            echo "XXX";
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
