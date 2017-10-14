<?php

namespace Frixs\Database;

use Frixs\Config\Config;

/**
 * Connection to the MySQL database with its CRUD methods
 * Singleton
 */
class MysqlConnection
{
    private static $_instance = null;
    private $_pdo,
        $_query,
        $_error = false,
        $_results,
        $_count = 0;

    /**
     * Connection to database
     */
    private function __construct()
    {
        try {
            $this->_pdo = new \PDO('mysql:host='. Config::get('database.mysql.host') .';dbname='. Config::get('database.mysql.db_name') .';charset=utf8', Config::get('database.mysql.username'), Config::get('database.mysql.password'));
                
            /* DEBUG attribute to show error messages */
            //$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Singleton model object, get instance of it
     *
     * @return object   Database instance
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new MysqlConnection();
        }
        return self::$_instance;
    }

    /**
     * Check if transaction is ON
     *
     * @return bool
     */
    public function inTransaction()
    {
        return $this->_pdo->inTransaction();
    }

    /**
     * Start transaction
     *
     * @return void
     */
    public function beginTransaction()
    {
        $this->_pdo->beginTransaction();
    }
        
    /**
     * RollBack transaction
     *
     * @return void
     */
    public function rollBack()
    {
        if ($this->inTransaction()) {
            $this->_pdo->rollBack();
        }
    }
        
    /**
     * Commit transaction
     *
     * @return void
     */
    public function commit()
    {
        $this->_pdo->commit();
    }

    /**
     * Execution of our query
     *
     * @param string $sql                   SQL query, params are replaced by question mark
     * @param array $params                 array with parametres (array is empty as default)
     * @return object                       query object
     */
    public function query($sql, $params = array())
    {
        $queryType = explode(' ', $sql)[0];
        $noQueryResults = ($queryType == 'SELECT') ? false : true;

        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            /** parameter position */
            $paramPos = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($paramPos, $param);
                    $paramPos++;
                }
            }

            if ($this->_query->execute()) {
                if ($noQueryResults) {
                    $this->_results = null;
                    $this->_count   = $this->_query->rowCount();
                } else {
                    $this->_results = $this->_query->fetchAll(\PDO::FETCH_OBJ);
                    $this->_count   = $this->_query->rowCount();
                }
            } else {
                $this->_error = true;
            }
        }

        return $this;
    }

    /**
     * Validation method for query methods
     *
     * @param string $action    type of action (SELECT/UPDATE/...)
     * @param string $table     table name
     * @param array $where      where condition separated in the array, f.e. array('username','=','Frixs','AND',...)
     * @param array $order      order condition separated in the array, f.e. array('name','DESC','lastname','ASC')
     * @param integer $limit    limit condition
     * @return object           query object
     */
    private function action($action, $table, $where = array(), $order = array(), $limit = 0)
    {
        // we need to know at least '1' to disable where statement or f.e. username = Frixs, 4th parameter can be AND or OR
        if (count($where) > 0) {
            $operators = array('=', '<>', '>', '<', '>=', '<=', 'LIKE');
                
            $whereCond = '';
            $values = array();
            $boolValidation = true;
            $x = 1; // types: 1-field, 2-operand, 3-value
            foreach ($where as $item) {
                switch ($x) {
                    case 1:
                        $whereCond .= $item;
                        break;
                    case 2:
                        if (!in_array($item, $operators)) {
                            $boolValidation = false;
                        }
                        $whereCond .= " ". $item;
                        break;
                    case 3:
                        $values[] = $item;
                        $whereCond .= ' ?';
                        break;
                    case 4:
                        $whereCond .= " ". $item ." ";
                        $x = 0; //reset
                        break;
                }
                $x++;
            }
                
            $limitCond = "";
            if ($limit > 0) {
                $limitCond = " LIMIT {$limit}";
            }
                
            $orderCond = "";
            if ($order) {
                $x = 0;
                $orderCond .= " ORDER BY";
                foreach ($order as $val) {
                    if ($x == 2) {
                        $orderCond .= ",";
                        $x = 0;
                    }
                    $orderCond .= " ". $val;
                    $x++;
                }
            }
                
            if ($boolValidation) {
                $sql = "{$action} FROM {$table} WHERE {$whereCond}" . $orderCond . $limitCond;

                if (!$this->query($sql, $values)->error()) {
                    return $this;
                }
            } else {
                $this->_error = true;
            }
        } else {
            $this->_error = true;
        }

        return $this;
    }

    /**
     * Method to easy to use SELECT query (SELECT ALL)
     *
     * @param string $table     table name
     * @param string $where     where condition separated in the array, f.e. array('username','=','Frixs','AND',...)
     * @param array $order      order condition separated in the array, f.e. array('name','DESC','lastname','ASC')
     * @param integer $limit    limit condition
     * @return object           query object
     */
    public function selectAll($table, $where = array(), $order = array(), $limit = 0)
    {
        return $this->action('SELECT *', $table, $where, $order, $limit);
    }

    /**
     * Method to easy to use SELECT query (SELECT ACCORDING TO FIELDS)
     *
     * @param string $where             where condition separated in the array, f.e. array('username','=','Frixs','AND',...)
     * @param array $order              order condition separated in the array, f.e. array('name','DESC','lastname','ASC')
     * @param array $selectedFields     selected data fields separated in the array
     * @param integer $limit            limit condition
     * @return object                   query object
     */
    public function select($table, $where = array(), $selectedFields = array(), $order = array(), $limit = 0)
    {
        return $this->action('SELECT '.implode(', ', $selectedFields), $table, $where, $order, $limit);
    }

    /**
     * Method to easy to use DELETE query
     *
     * @param string $table     table name
     * @param [type] $where     where condition separated in the array, f.e. array('username','=','Frixs','AND',...)
     * @return object           query object
     */
    public function delete($table, $where)
    {
        return $this->action('DELETE', $table, $where, array(), 0);
    }
        
    /**
         *  Method to insert data to database
         *
         *  @param table          name of a table
         *  @param fields         array f.e. - array('username'=>'Frixs', 'user_email'=>'frixs@gmail.com')
         */
    /**
     * Method to easy to use INSERT query
     *
     * @param string $table     table name
     * @param array $fields     selected data fields separated in the array with its values, f.e. array('username'=>'Frixs', 'user_email'=>'frixs@seznam.cz')
     * @return object           query object
     */
    public function insert($table, $fields = array())
    {
        $keys = array_keys($fields);
        $values = '';
        $x = 1; //number of go trough of cycle bellow, 1 because of we dont need ', ' on the end of a string
            
        foreach ($fields as $field) {
            $values .= '?';
            if ($x < count($fields)) {
                $values .= ', ';
            }
            $x++;
        }
            
        $sql = "INSERT INTO {$table} (`". implode('`, `', $keys) ."`) VALUES({$values})";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method to easy to use UPDATE query
     *
     * @param string $table     table name
     * @param array $fields     selected data fields separated in the array with its values, f.e. array('username'=>'Frixs', 'user_email'=>'frixs@seznam.cz')
     * @param array $where      where condition separated in the array, f.e. array('username','=','Frixs','AND',...)
     * @return object           query object
     */
    public function update($table, $fields = array(), $where = array())
    {
        // why 3? We need to know: field, operator and value (f.e. username = Frixs), 4th parameter can be AND or OR
        if (count($where) >= 3) {
            $values = array(); // array for both fields: fields for set and fields for where
            $set = '';
            $y = 1;
            foreach ($fields as $name => $value) {
                $set .= "{$name} = ?";
                if ($y < count($fields)) {
                    $set .= ', ';
                }
                $y++;
                    
                $values[] = $value; // first, we need to append variables for SET and after that for WHERE in the correct order.
            }
                
            $operators = array('=', '<>', '>', '<', '>=', '<=', 'LIKE');
                
            $whereCond = '';
            $boolValidation = true;
            $x = 1; // types: 1-field, 2-operand, 3-value
            foreach ($where as $item) {
                switch ($x) {
                    case 1:
                        $whereCond .= $item;
                        break;
                    case 2:
                        if (!in_array($item, $operators)) {
                            $boolValidation = false;
                        }
                        $whereCond .= " ".$item;
                        break;
                    case 3:
                        $values[] = $item;
                        $whereCond .= ' ?';
                        break;
                    case 4:
                        $whereCond .= " ".$item." ";
                        $x = 0; //reset
                        break;
                }
                $x++;
            }
                
            if ($boolValidation) {
                $sql = "UPDATE {$table} SET {$set} WHERE {$whereCond}";

                if (!$this->query($sql, $values)->error()) {
                    return true;
                }
            }
        }
            
        return false;
    }

    /**
     * Get query results
     *
     * @return object   query object
     */
    public function get()
    {
        return $this->_results;
    }
    
    /**
     * Get the first result/1st row
     *
     * @return object   query object
     */
    public function getFirst()
    {
        return $this->get()[0];
    }
    
    /**
     * Get the last inserted ID to the database table
     *
     * @return object   query object
     */
    public function lastInsertId()
    {
        return $this->_pdo->lastInsertId();
    }

    /**
     * Check if query went wrong
     *
     * @return bool     if error occured
     */
    public function error()
    {
        return $this->_error;
    }
    
    /**
     * Get number of selected rows
     *
     * @return integer  number of sleected rows
     */
    public function count()
    {
        return $this->_count;
    }
}

/* Examples of usage ***
    *************************
    * query()
    *************************
        $query = DB::getInstance()->query( "SELECT username FROM phpbb_users WHERE username = ?", array('Frixs') );
        if($query->error()){
            echo "Wrong command";
        }else{
            echo "OK!";
        }
    
    *************************
    * selectAll(), select(), delete()
    *************************
        $query = DB::getInstance()->select( 'phpbb_users', array('username','=','Frixs'), array('username','user_id') );
        if(!$query->count()){
            echo "Undefined user";
        }else{
            echo "User exists";
            //
            foreach($query->get() as $item)
            {
                echo $item->username, '<br>';
            }
            //
            echo $query->get()[0]->username;
            //
            echo $query->getFirst()->username;
        }
        
    *************************
    * insert()
    *************************
        $query = DB::getInstance()->insert( 'phpbb_users', array('username'=>'Frixs', 'user_email'=>'frixs@seznam.cz') );
        if($query){
            echo "Inserted!";
        }else{
            echo "Wrong command";
        }
        
    *************************
    * update()
    *************************
        $query = DB::getInstance()->update( 'phpbb_users', array("username"=>"Frixs"), array('username',
                                                                                              '=',
                                                                                              'Frixsik',
                                                                                              'AND',
                                                                                              'user_email',
                                                                                              '=',
                                                                                              'frixs@seznam.cz',
                                                                                            ) );

        if( $query ){
            echo "OK!";
        }else{
            echo "Wrong sql";
        }
*/
