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
	'logout' => array(
		'handler' => 'nlb/logout.php',
		'access' => array('authenticated user'),
	),
	'entity/%action/%eid' => array(
		'handler' => 'nlb/entity.php?action=%action&eid=%eid',
		'access' => array('anonymous user'),
	),
	'admin/entity' => array(
		'handler' => 'nlb/admin/entityAdmin.php?action=list',
		'access' => array('admin user'),
	),
	'admin/entity/create/%entity_type' => array(
		'handler' => 'nlb/admin/entityAdmin.php?action=create&entity_type=%entity_type',
		'access' => array('admin user'),
	),
	'admin/entity/edit/%eid' => array(
		'handler' => 'nlb/admin/entityAdmin.php?action=edit&eid=%eid',
		'access' => array('admin user'),
	),
	'admin/entity/delete/%eid' => array(
		'handler' => 'nlb/admin/entityAdmin.php?action=delete&eid=%eid',
		'access' => array('admin user'),
	),
	'admin/entity/set-status/%eid/%statusid' => array(
		'handler' => 'nlb/admin/entityAdmin.php?action=setstatus&eid=%eid&statusid=%statusid',
		'access' => array('admin user'),
	),
	'admin/entity/save' => array(
		'handler' => 'nlb/admin/entityAdmin.php?action=save',
		'access' => array('admin user'),
	)
);
