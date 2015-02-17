<?php

date_default_timezone_set('Europe/Brussels');

$db_config = array(
    'driver' => 'mysql',
    'username' => 'root',
    'password' => 'z09954',
    'schema' => 'gunther',
    
    'dsn' => array(
        'host' => 'localhost',
        'dbname' => 'gunther',
        'port' => '3306',
    )
);

$settings = array(
    'CP_API' => 'http://localhost:5050/api/40389981c6a54cb4a3b813a4961e249d/',
    'SB_API' => 'http://localhost:8081/api/596f43e20f719a6bf83ee7dc3f0370e1/?cmd=',
    'TVDB_API' => '919407757B63D836',
);
