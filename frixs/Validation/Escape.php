<?php

namespace Frixs\Validation;

/**
 * List of methods to escape inputs
 */
class Escape
{
    /**
     * Escape output to HTML
     *
     * @param string $string
     * @return string
     */
    public static function output($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Escape inputs
     *
     * @param string $string
     * @return void
     */
    public static function input($string)
    {
        return trim($string);
    }

    /**
     * Transfer URL address into HTML tag
     *
     * @param string $string
     * @return string
     */
    public static function urlToHtmlLink($string, $blank = true)
    {
        $secureProtocol = false;

        if (substr($string, 0, 5) == 'https') {
            $string = substr($string, 5);
            $secureProtocol = true;
        } elseif (substr($string, 0, 4) == 'http') {
            $string = substr($string, 4);
        }

        if (substr($string, 0, 3) == '://') {
            $string = substr($string, 3);
        }
    
        $url = '~(?:(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
        return preg_replace($url, '<a href="'. (($secureProtocol) ? 'https' : 'http') .'://$0" '. (($blank) ? 'target="_blank"' : '') .' title="$0">$0</a>', $string);
    }
}
