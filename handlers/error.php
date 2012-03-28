<?php

$vars = array(
	'type' => $su->prettyFromURL($_GET['t']),
);

$pageVars['title'] = $su->prettyFromURL($vars['type']).' Error';
$pageVars['content'] = $UI->renderTemplate('error.tpl', $vars);
