<?php

date_default_timezone_set('Europe/Brussels');

$settings = array(
    'CP_API' => 'http://localhost:5050/api/7ae73d9aebdb40c5be40dde03a940cbf/',
    'SB_API' => 'http://localhost:8081/api/042637520f2de5b4d11f798e0903529a/?cmd=',
    'TVDB_API' => '919407757B63D836',      #Your TheTvDB API key

    'TVDB_URL' => 'http://thetvdb.com',
    'CACHE_DIR' => 'cache/',    #cache directory for TheTvDB/IMDB
    'CACHE_TTL' => 86400,        #Cache time-to-live in seconds, default 1 day
    'DEFAULT_LANG' => 'en',     #default language

   'AUTH_DIGEST_FILE' => '/etc/nginx/webdav.auth',
   'LOG' => '/var/log/nginx/error.log',
   'LAST_LOG' => 10,  #get last 10 logs
);
