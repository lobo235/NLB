<?php

$schema = file_get_contents(NLB_SITE_ROOT.'schema/nlb.sql');
$queries = explode(';', $schema);

$counter = 0;

$vars = array();
$vars['successfulQueries'] = array();
$vars['failedQueries'] = array();

foreach($queries as $query)
{
	if($query != '')
	{
		$query = trim($query);
		$res = $DB->exec($query);
		if($res)
		{
			$vars['successfulQueries'][] = $query;
		}
		else
		{
			$vars['failedQueries'][] = $query;
		}
	}
}

$pageVars['title'] = 'Installation Results';
$pageVars['content'] = $UI->renderTemplate('install.tpl', $vars);
