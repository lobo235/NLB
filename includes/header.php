<?php

require_once('load_config.php');

// make sure NLB is installed
if($app->getVar('installed', 0) === 0)
{
	die('NLB has not yet been installed! Please visit the <a href="'.$app->urlRoot().'install.php">Install Page</a>.');
}

// Load classes that will be needed on almost every page
$GLOBALS['app']->loadClass('services', 'DatabaseService');
$GLOBALS['app']->loadClass('services', 'UIService');
$GLOBALS['app']->loadClass('dom', 'User');
$GLOBALS['app']->loadClass('services', 'LogService');
$GLOBALS['app']->loadClass('util', 'StringUtils');

// get an instance of the DatabaseService to communicate/use the database
$DB = DatabaseService::getInstance();

// get an instance of the UIService to be able to render UI components
$UI = UIService::getInstance();

// get an instance of the LogService class to be able to log/email messages
$Log = LogService::getInstance();

$su = StringUtils::getInstance();