<?php

// Determine which folder in 'sites' to use for this request
$siteDirectory = 'default';
// Exact Match (www.example.com)
if(is_dir(realpath(dirname(__FILE__).'/../sites/'.$_SERVER['HTTP_HOST'])))
{
	$siteDirectory = $_SERVER['HTTP_HOST'];
}
// Strip off the first subdomain and try to match (*.example.com)
elseif(substr_count($_SERVER['HTTP_HOST'], '.') > 0 && is_dir(realpath(dirname(__FILE__).'/../sites/'.preg_replace('/^[^\.]*?\./', '', $_SERVER['HTTP_HOST']))))
{
	$siteDirectory = preg_replace('/^.*?\./', '', $_SERVER['HTTP_HOST']);
}
// Strip off the last part of the domain name (e.g. .com, .net, .org) and try to match (www.example.*)
elseif(substr_count($_SERVER['HTTP_HOST'], '.') > 0 && is_dir(realpath(dirname(__FILE__).'/../sites/'.preg_replace('/\.[^\.]*?$/', '', $_SERVER['HTTP_HOST']))))
{
	$siteDirectory = preg_replace('/\.[^\.]*?$/', '', $_SERVER['HTTP_HOST']);
}
// Strip off the first subdomain and the last part of the domain name and try to match (*.example.*)
elseif(substr_count($_SERVER['HTTP_HOST'], '.') > 1 && is_dir(realpath(dirname(__FILE__).'/../sites/'.preg_replace('/\.[^\.]*?$|^[^.]*?\./', '', $_SERVER['HTTP_HOST']))))
{
	$siteDirectory = preg_replace('/\.[^\.]*?$|^[^.]*?\./', '', $_SERVER['HTTP_HOST']);
}

// Load our config file
if(file_exists(realpath(dirname(__FILE__).'/../sites/'.$siteDirectory.'/config/config.inc.php')))
{
	$noConfig = FALSE;
	require(realpath(dirname(__FILE__).'/../sites/'.$siteDirectory.'/config/config.inc.php'));
}
else
{
	$noConfig = TRUE;
}


if(NLB_LOG_PAGETIMES || NLB_DEBUG)
{
	// Load and start our PageTimerService
	class_exists('PageTimerService') || require_once(NLB_LIB_ROOT.'services/PageTimerService.class.php');
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

class_exists('App') || require_once(NLB_LIB_ROOT.'util/App.class.php');

// get an instance of the App class to be able to get info about this App
$app = App::getInstance();