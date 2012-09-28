<?php

class_exists('Node') || require(NLB_LIB_ROOT.'Node.class.php');

if($_GET['action'] == 'viewpost' && isset($_GET['id']))
{
	$vars = array();
	$vars['node'] = new Node($_GET['id']);
	$vars['user'] = new User($vars['node']->getUid());
	$pageVars['title'] = $vars['node']->getTitle();
	$pageVars['content'] = $UI->renderTemplate('blog-viewEntry.tpl', $vars);
}
elseif($_GET['action'] == 'list')
{
	echo 'Listing all blog entries';
}
