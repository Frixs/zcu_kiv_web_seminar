<?php

/**
 * There you can define what should load up as first
 */

use Config\Core\Config;
use Frixs\Language\Lang;

session_start();

new Config();
new Lang();