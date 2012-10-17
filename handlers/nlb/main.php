<?php

$vars = array(
	'user' => $user,
);

$pageVars['is_front'] = TRUE;
$pageVars['title'] = NLB_SITE_NAME;
$pageVars['content'] = $UI->renderTemplate('index.tpl', $vars, 3);
