<?php

if($_GET['action'] == 'viewpost' && isset($_GET['id']))
{
	$vars = array();
	$vars['node'] = new Node($_GET['id']);
	$vars['user'] = new User($vars['node']->uid);
	$pageVars['content'] = $UI->renderTemplate('blog-viewEntry.tpl', $vars);
}
elseif($_GET['action'] == 'list')
{
	echo 'Listing all blog entries';
}
