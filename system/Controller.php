<?php

class Controller
{
    const DEFAULT_CONTROLLER = "Home";
    const DEFAULT_ACTION = "index";
    const CONTROLLERPATH = 'controllers/';
    const CONTROLLERFILE = 'index.php';

    private $controller = self::DEFAULT_CONTROLLER;
    private $action = self::DEFAULT_ACTION;
    private $params = array();
    
    public function __construct()
    {
        $this->parseUri(); 
    }

    private function parseUri()
    {
        // strip the controllerfile out of the scriptname
        $scriptprefix = str_replace(self::CONTROLLERFILE, '', $_SERVER['SCRIPT_NAME']);
        $uri = str_replace(self::CONTROLLERFILE, '', $_SERVER['REQUEST_URI']);
        
        $n = strpos($uri, "?lang=");
        if ($n > 0){
            $_SESSION['lang'] = substr($uri, $n + 6, $n + 6 + 2);
            $uri = substr($uri, 0, $n);
        }

        // get the part of the uri, starting from the position after the scriptprefix
        $path = substr($uri, strlen($scriptprefix));

        // strip non-alphanumeric characters out of the path
        $path = preg_replace('/[^a-zA-Z0-9]\//', "", $path);

        // trim the path for /
        $path = trim($path, '/');

        // explode the $path into three parts to get the controller, action and parameters
        // the @-sign is used to supress errors when the function after it fails
        @list($controller, $action, $params) = explode("/", $path, 3);

        $this->setController($controller);
        if (isset($action)) {
            $this->setAction($action);
            if (isset($params)) {
                $this->setParams(explode("/", $params));
            }
        }
    }

    private function setController($controller)
    {
        $controller = ($controller) ? $controller : self::DEFAULT_CONTROLLER;

        $controllerfile = APPLICATION_PATH . self::CONTROLLERPATH . ucfirst(strtolower($controller)) . '.php';

        // check if controller file exists
        if (file_exists($controllerfile)) {
            require_once($controllerfile);
            $this->controller = $controller;
        } else {
            error_log("Controller '$controller' not found.");
            header ("HTTP/1.1 404 Not Found");
            exit;
        }

        return $this;
    }

    public function getController()
    {
        return $this->controller;
    }

    public static function sanitize($input)
    {
        if (is_array($input)) {
            for($i=0;$i<count($input);$i++)	{
                self::sanitize($input[$i]);
            }
        } else {
            $input = trim($input);
            $input = htmlentities($input,ENT_QUOTES,"UTF-8");
        }

        return $input;
    }

    private function setAction($action)
    {
        // check if method exists
        if (!method_exists($this->controller, $action)) {
            error_log("Action '$action' does not exist in class '$this->controller'.");
            header ("HTTP/1.1 404 Not Found");
            exit;
        } else {
            $this->action = $action;
        }

        return $this;
    }

    private function setParams(array $params)
    {
        // loop through each element of the parameters to urldecode it
        array_walk($params, create_function('&$val', '$val = urldecode($val);'));

        // some XSS filtering
        $this->params = self::sanitize($params);
        return $this;
    }

    public function run()
    {

        // checking the parameter count, using Reflection (http://www.php.net/reflection)
        $reflector = new ReflectionClass($this->controller);
        $method = $reflector->getMethod($this->action);
        $parameters = $method->getNumberOfRequiredParameters();

        if (($parameters) > count($this->params)) {
            error_log("Action '$this->action' in class '$this->controller' expects $parameters mandatory parameter(s), you only provided " . count($this->params) . ".");
            header ("HTTP/1.1 404 Not Found");
            exit;
        }

        // create an instance of the controller as an object
        $controller = new $this->controller();

        // call the method based on $this->action and the params
        call_user_func_array(array($controller, $this->action), $this->params);
    }
}