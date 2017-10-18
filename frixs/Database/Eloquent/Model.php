<?php

namespace Frixs\Database\Eloquent;

use Frixs\Database\Connection as DB;
use Frixs\Routing\Router;
use Frixs\Config\Config;

/**
 |  Default structure for your models
 |  ==============================================
 |________________________________________________

<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;

class <CLASSNAME> extends Model
{
    // Use Model parameters.
    use \Frixs\Database\Eloquent\ModelParameters;

    public function __construct($attributes = [])
    {
        $this->initModel();

        // You can override attributes from the Model class via SETs.
        //static::setTable('TABLE_NAME');
    }
}

__________________________________________________
*/
abstract class Model
{
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
}
