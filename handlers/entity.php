<?php

if(isset($_GET['eid']) && is_numeric(trim($_GET['eid'])))
{
	$vars = array();
	$vars['entity'] = new Entity($_GET['eid']);
	if($_GET['action'] == 'view')
	{
		$pageVars['content'] = $UI->renderTemplate('entity-view.tpl', $vars);
	}
	elseif($_GET['action'] == 'edit')
	{
		$pageVars['content'] = $UI->renderTemplate('entity-edit.tpl', $vars);
	}
}
else
{
	header('Location: /error/entity-not-found');
}