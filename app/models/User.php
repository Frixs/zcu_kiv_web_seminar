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
    // Use User's traits.
    use \Frixs\Auth\UserTrait;

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

    /**
     * Check if user is member of server.
     *
     * @param integer $serverid
     * @param integer $uid
     * @return boolean
     */
    public static function hasServerAccess($uid, $serverid) {
        if (!$uid || !$serverid) {
            Router::redirectToError(501, 'User::hasServerAccess();');
        }

        $query = self::db()->selectAll(UserGroup::getTable(), [
            'user_id', '=', $uid,
            'AND', 'server_id', '=', $serverid
        ], [], 1);

        if ($query->error()) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        if ($query->count()) {
            return true;
        }

        return false;
    }

    /**
     * Get bool if logged user is owner of the server.
     *
     * @param integer $serverid
     * @return boolean
     */
    public static function isServerOwner($serverid) {
        if (!$serverid) {
            return false;
        }
        
        $query = self::db()->select(Server::getTable(), [
            Server::getPrimaryKey(), '=', $serverid,
            'AND', 'owner', '=', Auth::id()
        ], ['owner']);
        
        if ($query->error()) {
            Router::redirectToError(501, "User::isServerOwner();");
        }

        if ($query->count()) {
            return true;
        }

        return false;
    }
}
