<?php

$routes = array(
	'blog' => array(
		'handler' => 'blog.php?action=list',
		'access' => array('anonymous user'),
	),
	'blog/%post_id' => array(
		'handler' => 'blog.php?action=viewpost&id=%post_id',
		'access' => array('anonymous user'),
	),
);