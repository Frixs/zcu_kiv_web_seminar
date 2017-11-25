<?php

return [
    'name'                              => 'Gamendar',
    'locale'                            => 'en',
    'developer_mode'                    => true,
    'root'                              => getAbsoluteRoot(),
    'root_rel'                          => substr(getRelativeRoot(), 0, -1),
    'root_images'                       => getAbsoluteRoot() . '/images',
    'root_images_rel'                   => getRelativeRoot() . 'images',
    'root_server_uploads_rel'           => getRelativeRoot() . 'storage/server',


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

        'UserGroup' => \App\Models\UserGroup::class,
        'Server' => \App\Models\Server::class,
    ],
];

function getAbsoluteRoot() {
    return (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443)
        ? "https://"
        : "http://"
    ) . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
}

/**
 * Get relative path to root.
 *
 * @return string
 */
function getRelativeRoot() {
    $output = '';
    $i = 0;

    // Calculate how much deep it is minus URL identifiers (f.e. request), which do not affect URL deep.
    for (; $i < substr_count($_SERVER['REDIRECT_QUERY_STRING'], '/') - substr_count($_SERVER['REDIRECT_QUERY_STRING'], '__'); $i++) {
        $output .= '../';
    }
    
    if ($i == 0) {
        $output .= './';
    }

    return $output;
}