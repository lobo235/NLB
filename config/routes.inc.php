<?php

$routes = array(
	'blog/%post_id' => array(
		'handler' => 'blog.php?id=%post_id',
		'access' => array('anonymous user'),
	),
);
