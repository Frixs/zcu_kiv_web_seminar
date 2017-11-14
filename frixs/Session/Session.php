<?php

namespace Frixs\Session;

class Session
{
    /**
     * Check if token exists
     *
     * @param string $name      session name
     * @return bool             existence of session
     */
    public static function exists($name)
    {
        return (isset($_SESSION[$name])) ? true : false;
    }
    
    /**
     * Create session
     *
     * @param string $name      session name
     * @param string $value     session value
     * @return string           session value
     */
    public static function put($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    /**
     * Get session
     *
     * @param string $name      session name
     * @return mixed            session value or null
     */
    public static function get($name)
    {
        return $_SESSION[$name] ? $_SESSION[$name] : null;
    }

    /**
     * Delete session
     *
     * @param string $name      session name
     * @return void
     */
    public static function delete($name)
    {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }
    
    /**
     * Flash info messages after form submission
     *
     * @param string $name      session name
     * @param string $string    message
     * @return string           if doesnts exist session will be created
     *                          else returns session value and immediately will be deleted
     */
    public static function flash($name, $string = null)
    {
        if ($string) {
            self::put($name, $string);
        } else {
            $session = self::get($name);
            self::delete($name);
            return $session;
        }

        return null;
    }
}

/* Examples ***
    *************************
    * flash()
    *************************
        * pre form submission:
            if( $validation->passed() )
            {
                Session::flash('success', 'You registered successfully!');
                Redirect::to('index.php');
            }
            
        * after form submitted: index.php:
            if( Session::exists('success') ){
                echo Session::flash('success');
            }
*/
