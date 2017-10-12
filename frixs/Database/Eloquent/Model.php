<?php
// TODO try to you __get __set
// TODO create assignTableName method
namespace Frixs\Database\Eloquent;

use Frixs\Database\Connection as DB;
use Frixs\Routing\Router;

abstract class Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected static $table;

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
     * Indicates if the model is launched.
     *
     * @var bool
     */
    public static $alreadyLaunched = false;

    /**
     * Create an instance.
     */
    public function __construct($attributes = [])
    {
    }

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
                $query->rollBack();
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
        return "tablename";
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
     * @return string
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
    public static function isAlreadyLaunched()
    {
        return static::$alreadyLaunched;
    }
}
