<?php

$dbOption = [
    'db.options' => [
        'driver' => 'pdo_mysql',
        'dbname' => '',
        'host' => '',
        'user' => '',
        'password' => ''
    ]
];

$swiftMaillerConfig =
    [
        'swiftmailer.options' =>
            [
                'host' => '',
                'port' => 465,
                'username' => '',
                'password' => '',
                'encryption' => 'ssl',
                'auth_mode' => 'login'
            ]
    ];