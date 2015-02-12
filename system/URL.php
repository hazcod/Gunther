<?php
class URL
{
    // returns the current controller, based on the URI
    public static function getCurrentPath()
    {
        $path =  substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME'])) + 1); 
        $path = preg_replace('#(' . preg_quote('?lang=') . '.{2})#', '', $path);
        $path = trim($path, '/');

        // if no scriptfile in de request_uri, we assume index.php is called
        $path = ($path) ? $path : 'index.php';
        
        return $path;
    }

    public static function base_uri($uri = '')
    {
        return str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) . $uri;
    }
}
