<?php

include('header.php');

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
		$res = DatabaseService::getInstance()->exec($query);
		if($res)
		{
			$vars['successfulQueries'] = $query;
		}
		else
		{
			$vars['failedQueries'] = $query;
		}
	}
}

print $UI->renderTemplate('install.tpl', $vars);

include('footer.php');
