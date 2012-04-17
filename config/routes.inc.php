<?php

$routes = array(
	'blog' => array(
		'handler' => 'custom/blog.php?action=list',
		'access' => array('anonymous user'),
	),
	'blog/%post_id' => array(
		'handler' => 'custom/blog.php?action=viewpost&id=%post_id',
		'access' => array('anonymous user'),
	),
);