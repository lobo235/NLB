<?php

class_exists('Node') || require_once(NLB_LIB_ROOT.'dom/Node.class.php');

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