<?php

return [
    'name' => 'Gamendar',
    'locale' => 'en',
    'developer_mode' => true,
    'root' => ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\'),
    'root_images_rel' => 'images/',


    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */
    'aliases' => [
        'Config' => \Frixs\Config\Config::class,
        'Input' => \Frixs\Http\Input::class,
        'Request' => \Frixs\Http\Request::class,
        'Token' => \Frixs\Http\Token::class,
        'Auth' => \Frixs\Auth\Auth::class,
    ],
];
