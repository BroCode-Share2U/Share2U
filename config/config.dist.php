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

$swiftMailerConfig =
    [
        'swiftmailer.options' =>
            [
                'host' => '',
                'port' => 465,
                'username' => '',
                'password' => '',
                'encryption' => 'ssl',
                'auth_mode' => 'login',
                'stream_context_options' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false
                    ]
                ]
            ]
    ];