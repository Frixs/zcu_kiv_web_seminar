<?php

namespace Frixs\Http;

/**
 *  Access to inputs. You dont need to call $_POST['something'] or $_GET['something'],
 *  you can use:
 *  Input::get('something');
 *
 *  or check if form has been submitted:
 *  Input::check('token');
 */
class Input
{
    /**
     * Check submission
     *
     * @param string $type      post or get
     * @return bool             bool of input existance
     */
    public static function exists($type = 'post')
    {
        switch ($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;
            case 'get':
                return (!empty($_GET)) ? true : false;
                break;
            default:
                return false;
        }
    }

    /**
     * Get input value
     *
     * @param [type] $item  input name
     * @param string $type  input type
     * @return string       value of the input or null
     */
    public static function get($item, $type = 'post')
    {
        if (isset($_POST[$item]) && $type == 'post') {
            return $_POST[$item];
        } elseif (isset($_GET[$item]) && $type == 'get') {
            return $_GET[$item];
        }
        return null;
    }
}
