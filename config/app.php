<?php

return [
    'name' => 'Action Calendar',
    'locale' => 'en',
    'developer_mode' => true,
    'root' => 'http://'. $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\'),
];
