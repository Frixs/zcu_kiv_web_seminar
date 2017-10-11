<?php

/**
 * There you can define global functions.
 * It is very useful f.e. if you need to call some Lang or Confing in your views. You dont need to define 'use' path.
 */

use Frixs\Language\Lang;

function langGet($path, $parameters = [])
{
    Lang::get($path, $parameters);
}
