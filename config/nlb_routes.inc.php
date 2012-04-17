<?php

$nlb_routes = array(
	'index' => array(
		'handler' => 'nlb/main.php',
		'access' => array('anonymous user'),
	),
	'error/%type' => array(
		'handler' => 'nlb/error.php?t=%type',
		'access' => array('anonymous user'),
	),
	'phpinfo' => array(
		'handler' => 'nlb/phpinfo.php',
		'access' => array('admin user'),
	),
	'login' => array(
		'handler' => 'nlb/login.php',
		'access' => array('anonymous user'),
	),
	'processLogin' => array(
		'handler' => 'nlb/login.php',
		'access' => array('anonymous user'),
	),
	'entity/%action/%eid' => array(
		'handler' => 'nlb/entity.php?action=%action&eid=%eid',
		'access' => array('anonymous user'),
	),
);
