<?php
// Load our config file
require(realpath(dirname(__FILE__).'/../config/config.inc.php'));

if(LOG_PAGETIMES || DEBUG)
{
	// Load and start our PageTimerService
	class_exists('PageTimerService') || require(NLB_LIB_ROOT.'PageTimerService.class.php');
	$PageTimer = new PageTimerService();
	$PageTimer->start();
}

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
class_exists('DatabaseService') || require(NLB_LIB_ROOT.'DatabaseService.class.php');
class_exists('UIService') || require(NLB_LIB_ROOT.'UIService.class.php');
class_exists('User') || require(NLB_LIB_ROOT.'User.class.php');
class_exists('LogService') || require(NLB_LIB_ROOT.'LogService.class.php');
class_exists('StringUtils') || require(NLB_LIB_ROOT.'StringUtils.class.php');

// get an instance of the DatabaseService to communicate/use the database
$DB = DatabaseService::getInstance();

// get an instance of the UIService to be able to render UI components
$UI = UIService::getInstance();

// get an instance of the LogService class to be able to log/email messages
$Log = LogService::getInstance();

$su = StringUtils::getInstance();