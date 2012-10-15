<?php

$vars = array(
	'user' => $user,
);

$pageVars['is_front'] = TRUE;
$pageVars['title'] = 'Site Index';
$pageVars['content'] = $UI->renderTemplate('index.tpl', $vars, 3);
