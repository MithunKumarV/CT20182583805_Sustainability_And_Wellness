<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 'on');

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

date_default_timezone_set('Asia/Kolkata');

//echo '---'.APPLICATION_PATH; exit;
/** Zend_Application */
require_once 'Zend/Application.php';  

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();