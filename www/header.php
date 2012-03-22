<?php
// Load our config file
require_once(realpath(dirname(__FILE__).'/../config/config.inc.php'));

// Load and start our PageTimerService
require_once(NLB_LIB_ROOT.'PageTimerService.class.php');
$PageTimer = new PageTimerService();
$PageTimer->start();

// Turn error reporting on/off
if(SHOW_ERRORS)
{
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}
else
{
	error_reporting(0);
	ini_set('display_errors', 0);
}

// Load classes that will be needed on almost every page
require_once(NLB_LIB_ROOT.'DatabaseService.class.php');
require_once(NLB_LIB_ROOT.'UIService.class.php');
require_once(NLB_LIB_ROOT.'User.class.php');

// get an instance of the DatabaseService to communicate/use the database
try
{
	$DB = DatabaseService::getInstance();
}
catch(DatabaseServiceException $e)
{
	if($_SERVER['REQUEST_URI'] != '/error.php?t=db')
	{
		header('Location: /error.php?t=db');
		exit();
	}
}

// get an instance of the UIService to be able to render UI components
$UI = UIService::getInstance();
