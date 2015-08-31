<?php

date_default_timezone_set('Europe/Brussels');

$settings = array(
    'MEDIA_LOC' => '/mnt/media/',

    'TVDB_URL' => 'http://thetvdb.com',

    'cache_location' => 'cache/',    #cache directory for TheTvDB/IMDB

    'movie_provider' => 'CouchPotato',        #Cache time-to-live in seconds, default 1 day
    'movie_settings' => array(
	'api' => '1e501582e6944bd388c1b479720d2f2f',
	'location' => 'http://localhost:5050/',
    ),
    'serie_provider' => '',
    'serie_settings' => array(
	'api' => '',
	'location' => 'http://localhost:8083/',
    ),

   'DEFAULT_LANG' => 'en',     #default language

   'REPORT_BAD_LOGIN' => true,   #set to false if you don't want to hide failed login attempts

   'DB_LOC' => '/etc/nginx/gunther.db',
   'AUTH_DIGEST_FILE' => '/etc/nginx/webdav.auth',
   'LOG' => '/var/log/nginx/error.log',
   'LAST_LOG' => 10,  #get last 10 logs

    'TVDB_API' => '919407757B63D836',      #Your TheTvDB API key
);
