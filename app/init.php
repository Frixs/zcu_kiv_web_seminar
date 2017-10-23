<?php

/**
 * There you can define what should load up as first
 */

use Frixs\Config\Config;
use Frixs\Language\Lang;
use App\Models\Group;

session_start();

Config::init();
Lang::init();

Group::loadGroupData();