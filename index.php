<?php
// debug
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

// booting up
session_start();

define('APPLICATION_PATH', 'application/');
define('FRAMEWORK_PATH', 'system/');

// libraries and helpers
require_once(FRAMEWORK_PATH . 'Load.php');
require_once(FRAMEWORK_PATH . 'URL.php');
require_once(FRAMEWORK_PATH . 'DB.php');
12	require_once(FRAMEWORK_PATH . 'Controller.php');
…	
18	require_once(FRAMEWORK_PATH . 'Core_db.php');
require_once(FRAMEWORK_PATH . 'Template.php');
require_once(FRAMEWORK_PATH . 'Form.php');

// abstract classes
require_once(FRAMEWORK_PATH . 'Core_controller.php');

// application config and abstract classes
require_once(APPLICATION_PATH . 'config.php');

// initialising front controller
$controller = new Controller();

// run forest, run.
$controller->run();
