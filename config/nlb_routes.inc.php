<?php

$nlb_routes = array(
	'index' => array(
		'handler' => 'nlb/main.php',
		'access' => array('anonymous user'),
	),
	'theme-asset/%theme/%file' => array(
		'handler' => 'nlb/theme_asset.php?theme=%theme&f=%file',
		'access' => array('anonymous user'),
		'options' => array('supress theme'),
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
	'admin/db-objects/%type' => array(
		'handler' => 'nlb/admin/dbObjectAdmin.php?action=list&type=%type',
		'access' => array('admin user'),
	),
	'admin/db-object/create/%type' => array(
		'handler' => 'nlb/admin/dbObjectAdmin.php?action=create&type=%type',
		'access' => array('admin user'),
	),
	'admin/db-object/edit/%type/%object_id' => array(
		'handler' => 'nlb/admin/dbObjectAdmin.php?action=edit&type=%type&object_id=%object_id',
		'access' => array('admin user'),
	),
	'admin/db-object/delete/%type/%object_id' => array(
		'handler' => 'nlb/admin/dbObjectAdmin.php?action=delete&type=%type&object_id=%object_id',
		'access' => array('admin user'),
	),
	'admin/db-object/%type/save' => array(
		'handler' => 'nlb/admin/dbObjectAdmin.php?action=save&type=%type',
		'access' => array('admin user'),
	),
	'admin/db-object/%type/set-status/%object_id/%status_id' => array(
		'handler' => 'nlb/admin/dbObjectAdmin.php?action=setstatus&type=%type&object_id=%object_id&statusid=%status_id',
		'access' => array('admin user'),
	),
	'node/%nid' => array(
		'handler' => 'nlb/node.php?nid=%nid',
		'access' => array('anonymous user'),
	),
	'user' => array(
		'handler' => 'nlb/user_dashboard.php',
		'access' => array('authenticated user'),
	),
	'faq' => array(
		'handler' => 'nlb/faq.php',
		'access' => array('anonymous user'),
	),
);
