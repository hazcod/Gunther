<?php

date_default_timezone_set('Europe/Brussels');

$db_config = array(
    'driver' => 'mysql',    #or pgsql
    'username' => 'gunther',
    'password' => '<PASS>', #The value you modified in the SQL script
    'schema' => 'public',
    'dsn' => array(
        'host' => 'localhost',
        'dbname' => 'gunther',
        'port' => '3306',
    )
);

$settings = array(
    'CP_API' => 'http://localhost:5050/api/<API>/',
    'SB_API' => 'http://localhost:8081/api/<API>/?cmd=',
    'TVDB_API' => '<API>',      #Your TheTvDB API key

    'TVDB_URL' => 'http://thetvdb.com',
    'CACHE_DIR' => 'cache/',    #cache directory for TheTvDB/IMDB
    'CACHE_TTL' => 86400,        #Cache time-to-live in seconds, default 1 day
    'DEFAULT_LANG' => 'en',     #default language
);
