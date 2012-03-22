<?php

require_once(realpath(dirname(__FILE__).'/../config/config.inc.php'));
require_once(NLB_LIB_ROOT.'PageTimerService.class.php');
$PageTimer = new PageTimerService();
$PageTimer->start();

if(SHOW_ERRORS)
{
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

require_once(NLB_LIB_ROOT.'DatabaseService.class.php');
require_once(NLB_LIB_ROOT.'UIService.class.php');
require_once(NLB_LIB_ROOT.'User.class.php');

try
{
	$DB = DatabaseService::getInstance();
}
catch(DBException $e)
{
	if($_SERVER['REQUEST_URI'] != '/error.php?t=db')
	{
		header('Location: /error.php?t=db');
		exit();
	}
}

$UI = UIService::getInstance();
