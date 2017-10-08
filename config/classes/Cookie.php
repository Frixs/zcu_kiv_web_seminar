<?php

class Cookie
{
    /**
     * Check if cookie exists
     *
     * @param string $name      cookie name
     * @return bool             boolean if cookie exists
     */
    public static function exists($name)
    {
        return (isset($_COOKIE[$name])) ? true : false;
    }

    /**
     * Get cookie value
     *
     * @param string $name      cookie name
     * @return string           cookie value
     */
    public static function get($name)
    {
        return $_COOKIE[$name];
    }
        
    /**
         *  Method to declare cookie
         *
         *  @param name     cookie name
         *  @param value    cookie value
         *  @param expiry   cookie expiration
         *  @return         status of cookie creation
         */
    /**
     * Declare cookie
     *
     * @param string $name      cookie name
     * @param string $value     cookie value
     * @param integer $expiry   cookie expire time
     * @return bool             success of declaration
     */
    public static function put($name, $value, $expiry)
    {
        if (setcookie($name, $value, time() + $expiry, '/')) {
            return true;
        }
        return false;
    }
    
    /**
     * Delete cookie
     *
     * @param string $name      cookie name
     * @return bool             success of deletion
     */
    public static function delete($name)
    {
        self::put($name, '', time() - 1);
    }
}
