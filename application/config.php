<?php

date_default_timezone_set('Europe/Brussels');

$db_config = array(
    'driver' => 'mysql',
    'username' => '_USER_',
    'password' => '_PASSWORD_',
    'schema' => '_DATABASE_',
    
    'dsn' => array(
        'host' => 'localhost',
        'dbname' => '_DATABASE_',
        'port' => '3306', //default SQL port
    )
);

$settings = array(
	'media_location' => '/media/storage',
	'salt1' => 'pumpkin',
	'salt2' => 'spice',
);
