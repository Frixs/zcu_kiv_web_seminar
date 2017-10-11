<?php

return [
    'name' => 'Action Calendar',
    'locale' => 'en',
    'developer_mode' => true,
    'root' => ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\'),
];
