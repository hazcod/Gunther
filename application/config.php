<?php

date_default_timezone_set('Europe/Brussels');

$db_config = array(
    'driver' => 'mysql',
    'username' => '_USER_',
    'password' => '_PASSWORD_',
    'schema' => 'gunther',
    
    'dsn' => array(
        'host' => 'localhost',
        'dbname' => 'gunther',
        'port' => '3306',
    )
);

$settings = array(
    'CP_API' => 'http://localhost:5050/api/<API>/',
    'SB_API' => 'http://localhost:8081/api/<API>/?cmd=',
    'TVDB_API' => '<API>',
);
