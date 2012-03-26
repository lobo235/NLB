<?php

$routes = array(
	'blog/%1' => array(
		'handler' => 'blog.php?id=%1',
		'access' => array('anonymous user'),
	),
);
