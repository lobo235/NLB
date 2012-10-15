<?php

// Determine which folder in 'sites' to use for this request
$siteDirectory = 'default';
if(is_dir(realpath(dirname(__FILE__).'/../sites/'.$_SERVER['HTTP_HOST'])))
{
	$siteDirectory = $_SERVER['HTTP_HOST'];
}
elseif(substr_count($_SERVER['HTTP_HOST'], '.') > 0 && is_dir(realpath(dirname(__FILE__).'/../sites/'.preg_replace('/^[^\.]*?\./', '', $_SERVER['HTTP_HOST']))))
{
	$siteDirectory = preg_replace('/^.*?\./', '', $_SERVER['HTTP_HOST']);
}
elseif(substr_count($_SERVER['HTTP_HOST'], '.') > 0 && is_dir(realpath(dirname(__FILE__).'/../sites/'.preg_replace('/\.[^\.]*?$/', '', $_SERVER['HTTP_HOST']))))
{
	$siteDirectory = preg_replace('/\.[^\.]*?$/', '', $_SERVER['HTTP_HOST']);
}
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