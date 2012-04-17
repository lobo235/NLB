<?php

if(LOG_MEMORY_USAGE || LOG_PAGETIMES || DEBUG)
{
	$params = array(
		':path' => $_SERVER['REQUEST_URI'],
		':gentime' => NULL,
		':referrer' => (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''),
		':user_agent' => $_SERVER['HTTP_USER_AGENT'],
		':peak_mem_usage' => NULL,
	);
	
	if(LOG_MEMORY_USAGE || DEBUG)
	{
		$params[':peak_mem_usage'] = memory_get_peak_usage();
		if(DEBUG)
		{
			echo "\n<!-- peak memory usage: ".$params[':peak_mem_usage']." bytes -->\n";
		}
	}

	if(LOG_PAGETIMES || DEBUG)
	{
		$PageTimer->stop();
		$mainGenTime = $PageTimer->getGenTime();
		$params[':gentime'] = $mainGenTime;
		if(DEBUG)
		{
			echo "\n<!-- generated in $mainGenTime seconds -->\n";
		}
	}
	
	if(DEBUG)
	{
		$PageTimer->start();
	}
	$query = "INSERT INTO `page_stats` (`path`,`gentime`,`referrer`,`user_agent`,`peak_mem_usage`,`datetime`) VALUES (:path,:gentime,:referrer,:user_agent,:peak_mem_usage,UTC_TIMESTAMP())";
	$DB->execUpdate($query, $params);
	if(DEBUG)
	{
		$PageTimer->stop();
		$pagetimeQueryGenTime = $PageTimer->getGenTime();
		echo "\n<!-- page_stats insert query took $pagetimeQueryGenTime seconds for a total of ".($mainGenTime+$pagetimeQueryGenTime)." seconds -->\n";
	}
}
