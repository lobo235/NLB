<?php

$nlb_routes = array(
	'index' => array(
		'handler' => 'main.php',
		'access' => array('anonymous user'),
	),
	'error/%1' => array(
		'handler' => 'error.php?t=%1',
		'access' => array('anonymous user'),
	),
	'install' => array(
		'handler' => 'install.php',
		'access' => array('admin user'),
	),
	'phpinfo' => array(
		'handler' => 'phpinfo.php',
		'access' => array('admin user'),
	),
	'login' => array(
		'handler' => 'login.php',
		'access' => array('anonymous user'),
	),
	'processLogin' => array(
		'handler' => 'login.php',
		'access' => array('anonymous user'),
	),
);
