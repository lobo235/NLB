<?php

// Determine which folder in 'sites' to use for this request
$siteFolder = 'default';
if(is_dir(realpath(dirname(__FILE__).'/../sites/'.$_SERVER['HTTP_HOST'])))
{
	$siteFolder = $_SERVER['HTTP_HOST'];
}

// Load our config file
require(realpath(dirname(__FILE__).'/../sites/'.$siteFolder.'/config/config.inc.php'));

if(NLB_LOG_PAGETIMES || NLB_DEBUG)
{
	// Load and start our PageTimerService
	class_exists('PageTimerService') || require(NLB_LIB_ROOT.'services/PageTimerService.class.php');
	$PageTimer = new PageTimerService();
	$PageTimer->start();
}

// Turn error reporting on/off
if(NLB_SHOW_ERRORS)
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
class_exists('DatabaseService') || require(NLB_LIB_ROOT.'services/DatabaseService.class.php');
class_exists('UIService') || require(NLB_LIB_ROOT.'services/UIService.class.php');
class_exists('User') || require(NLB_LIB_ROOT.'dom/User.class.php');
class_exists('LogService') || require(NLB_LIB_ROOT.'services/LogService.class.php');
class_exists('StringUtils') || require(NLB_LIB_ROOT.'util/StringUtils.class.php');
class_exists('App') || require(NLB_LIB_ROOT.'util/App.class.php');

// get an instance of the DatabaseService to communicate/use the database
$DB = DatabaseService::getInstance();

// get an instance of the App class to be able to get info about this App
$app = App::getInstance();

// get an instance of the UIService to be able to render UI components
$UI = UIService::getInstance();

// get an instance of the LogService class to be able to log/email messages
$Log = LogService::getInstance();

$su = StringUtils::getInstance();