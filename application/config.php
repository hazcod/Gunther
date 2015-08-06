<?php

date_default_timezone_set('Europe/Brussels');

$settings = array(
//    'CP_API' => 'http://localhost:5053/api/API-KEY-HERE/',
//    'SB_API' => 'http://localhost:8083/api/API-KEY-HERE/?cmd=',
//    'TVDB_API' => 'TVDB-API-KEY-HERE',      #Your TheTvDB API key

    'MEDIA_LOC' => '/mnt/media/',

    'TVDB_URL' => 'http://thetvdb.com',
    'CACHE_DIR' => 'cache/',    #cache directory for TheTvDB/IMDB
    'CACHE_TTL' => 86400,        #Cache time-to-live in seconds, default 1 day
    'DEFAULT_LANG' => 'en',     #default language

    'REPORT_BAD_LOGIN' => true,   #set to false if you don't want to hide failed login attempts

   'DB_LOC' => '/etc/nginx/gunther.db',
   'AUTH_DIGEST_FILE' => '/etc/nginx/webdav.auth',
   'LOG' => '/var/log/nginx/error.log',
   'LAST_LOG' => 10,  #get last 10 logs

    'CP_API' => 'http://localhost:5050/api/b86f3e4f2f2f461e8a99b5ea4e5c2e30/',
    'SB_API' => 'http://localhost:8081/api/e749747b330ec7d8ad8545b8e4cea99b/?cmd=',
    'TVDB_API' => '919407757B63D836',      #Your TheTvDB API key
);
