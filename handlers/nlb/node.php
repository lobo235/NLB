<?php

$GLOBALS['app']->loadClass('dom', 'Node');

if(isset($_GET['nid']) && is_numeric(trim($_GET['nid'])))
{
	$vars = array();
	$vars['node'] = new Node($_GET['nid']);
	$pageVars['title'] = $vars['node']->getTitle();
	$pageVars['content'] = $UI->renderTemplate('node-view.tpl', $vars);
}
else
{
	header('Location: /error/node-not-found');
	exit();
}