<?php

return array(
    // The drivers
    'driver' => array('Simpleauth'),

    // Set to true to allow multiple logins
    'verify_multiple_logins' => true,

    // Use your own salt for security reasons
    'salt' => 'Th1s=mY0Wn_$@|+',

    'remember_me' => array(
        'enabled' => true,
        'cookie_name' => 'rmcookie',
        'expiration' => 86400*310
    )
);