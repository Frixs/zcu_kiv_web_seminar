<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;

use Frixs\Auth\Auth;
use Frixs\Routing\Router;
use Frixs\Language\Lang;

class User extends Model
{
    // Use Model parameters.
    use \Frixs\Database\Eloquent\ModelParameters;

    /**
     * Create an instance of the model.
     */
    public function __construct($attributes = [])
    {
        $this->initModel();

        // You can override attributes from the Model class via SETs.
        //static::setTable('TABLE_NAME');
    }

    /**
     * Get user data by ID
     *
     * ---
     * Use: User::get(<ID>)->username;
     * ---
     *
     * @param integer $uid
     * @return object
     */
    public static function get($uid)
    {
        $query = self::db()->selectAll(self::getTable(), [self::getPrimaryKey(), '=', $uid], [], 1);
        return $query->getFirst();
    }

    /**
     * Register user to the table.
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $method        VERIFY|NOVERIFY|QUICK
     * @return void
     */
    public static function register($username, $email, $password, $method = 'VERIFY')
    {
        switch ($method) {
            case 'VERIFY':
                self::registerVerify($username, $email, $password);
                break;
            case 'NOVERIFY':
                self::registerNoVerify($username, $email, $password);
                break;
            case 'QUICK':
                self::registerQuick($username, $email, $password);
                break;
            default:
                Router::redirectToError(501, Lang::get('error.undefined_registration_method'));
        }
    }

    /**
     * Register with email verification.
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return void
     */
    private static function registerVerify($username, $email, $password)
    {
        Router::redirectToError(501, 'User::registerVerify();');
    }
    
    /**
     * Register without verification.
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return void
     */
    private static function registerNoVerify($username, $email, $password)
    {
        $salt = Auth::generateSalt();

        $query = self::db()->insert(self::getTable(), [
            'username'          => $username,
            'username_clean'    => strtolower($username),
            'email'             => $email,
            'password'          => Auth::hashPassword($password, $salt),
            'lastvisit'         => time(),
            'registered'        => 1,
            'register_time'     => time(),
            'verified'          => 0,
            'actkey'            => '',
            'form_salt'         => $salt
        ]);

        if (!$query) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }
    }
    
    /**
     * Quick registration, temporary.
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return void
     */
    private static function registerQuick($username, $email, $password)
    {
        Router::redirectToError(501, 'User::registerQuick();');
    }

    /**
     * Generate activation key for a registration.
     *
     * @return void
     */
    protected static function generateActKey() {
        return;
    }
}
