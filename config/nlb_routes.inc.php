<?php

$nlb_routes = array(
	'index' => array(
		'handler' => 'main.php',
		'access' => array('anonymous user'),
	),
	'error/%type' => array(
		'handler' => 'error.php?t=%type',
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
	'entity/%action/%eid' => array(
		'handler' => 'entity.php?action=%action&eid=%eid',
		'access' => array('anonymous user'),
	),
);
