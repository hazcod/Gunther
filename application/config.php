<?php

date_default_timezone_set('Europe/Brussels');

$settings = array(
    'CP_API' => 'http://192.168.0.210:5053/api/72cb07d4472343448cd1477259b6664b/',
    'SB_API' => 'http://192.168.0.210:8083/api/218a9ace35adac6caffb1c873d5929c2/?cmd=',
    'TVDB_API' => '67B935BD93959F7A',      #Your TheTvDB API key

    'TVDB_URL' => 'http://thetvdb.com',
    'CACHE_DIR' => 'cache/',    #cache directory for TheTvDB/IMDB
    'CACHE_TTL' => 86400,        #Cache time-to-live in seconds, default 1 day
    'DEFAULT_LANG' => 'en',     #default language

   'AUTH_DIGEST_FILE' => '/etc/nginx/webdav.auth',
   'LOG' => '/var/log/nginx/error.log',
   'LAST_LOG' => 10,  #get last 10 logs
);
