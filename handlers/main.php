<?php

$vars = array(
	'user' => $user,
);

$pageVars['title'] = 'Site Index';
$pageVars['content'] = $UI->renderTemplate('index.tpl', $vars);
