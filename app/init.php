<?php

/**
 * There you can define what should load up as first
 */

use Frixs\Config\Config;
use Frixs\Language\Lang;

session_start();

Config::init();
Lang::init();