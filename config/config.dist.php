<?php

$dbOption = [
    'db.options' => [
        'driver' => 'pdo_mysql',
        'dbname' => '',
        'host' => '',
        'user' => '',
        'password' => '',
        'driverOptions' => [
            1002 => 'SET NAMES utf8'
        ]
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