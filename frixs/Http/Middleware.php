<?php

namespace Frixs\Http;

use Frixs\Config\Config;

/**
 *  Main middleware with basic property.
 */
abstract class Middleware
{
    abstract public static function validate($parameters = []);
}
