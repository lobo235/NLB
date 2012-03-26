<?php

$vars = array(
	'type' => $_GET['t'],
);

print $UI->renderTemplate('error.tpl', $vars);
