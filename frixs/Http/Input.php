<?php

namespace Frixs\Http;

use Frixs\Routing\Router;
use Frixs\Language\Lang;

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
     * Returns input array - $_POST, $_GET
     *
     * @param string $type
     * @return array
     */
    private static function &getDirectInputArray($type)
    {
        switch ($type) {
            case 'post':
                return $_POST;
            case 'get':
                return $_GET;
        }

        Router::redirectToError(501, Lang::get('error.undefined_input_type'));
    }

    /**
     * Returns input array like $_POST or $_GET
     *
     * @param string $type
     * @return array or null
     */
    public static function all($type)
    {
        return self::getDirectInputArray($type);
    }

    /**
     * Set value in direct input array.
     *
     * @param string $type
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public static function set($type, $key, $value)
    {
        self::getDirectInputArray($type)[$key] = $value;
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

    /**
     * Method to get file data from submitted form.
     *
     * @param [type] $item
     * @param string $returnData
     * @return mixed
     */
    public static function getFileData($item, $returnData = "basename") {
        if (isset($_FILES[$item])) {
            switch ($returnData) {
                case "basename":
                    return basename($_FILES[$item]["name"]);
                    break;
                case "extension":
                    return pathinfo(basename($_FILES[$item]["name"]), PATHINFO_EXTENSION);
                    break;
                case "size":
                    return $_FILES[$item]["size"];
                    break;
                case "dimension":
                    return getimagesize($_FILES[$item]["tmp_name"]); // return array with 2 values (width, height)
                    break;
                case "tempname":
                    return $_FILES[$item]["tmp_name"];
                    break;
            }
        }
        
        return false;
    }
}
