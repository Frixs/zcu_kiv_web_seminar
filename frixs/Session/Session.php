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
         *  Method to flash information messages after form submission
         *
         *  @param name     session name
         *  @param string   message
         *  @return         create a session if the session doesnt exist.
         *                  message if session exists and immediately delete one.
         */
    /**
     * Flash info messages after form submission
     *
     * @param string $name      session name
     * @param string $string    message
     * @return string           if doesnts exist session will be created
     *                          else returns session value and immediately will be deleted
     */
    public static function flash($name, $string = '')
    {
        if (self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $string);
        }

        return '';
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
