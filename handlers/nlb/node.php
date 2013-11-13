<?php

$GLOBALS['app']->loadClass('dom', 'Node');

if(isset($_GET['nid']) && is_numeric(trim($_GET['nid'])))
{
	$node = new Node($_GET['nid']);
	if($node->getStatus() == 0)
	{
		if(!$userService->userHasRole($user, 'admin user'))
		{
			header('Location: /error/node-not-found');
			exit();
		}
		else
		{
			$pageVars['titleClasses'][] = 'unpublished';
		}
	}
	$vars = array();
	$vars['node'] = $node;
	$pageVars['title'] = $vars['node']->getTitle();
	$pageVars['content'] = $UI->renderTemplate('node-view.tpl', $vars);
}
else
{
	header('Location: /error/node-not-found');
	exit();
}