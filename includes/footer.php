<?php

if(NLB_LOG_MEMORY_USAGE || NLB_LOG_PAGETIMES || NLB_DEBUG)
{
	$params = array(
		':path' => $_SERVER['REQUEST_URI'],
		':gentime' => NULL,
		':referrer' => (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''),
		':user_agent' => $_SERVER['HTTP_USER_AGENT'],
		':peak_mem_usage' => NULL,
	);
	
	if(NLB_LOG_MEMORY_USAGE || NLB_DEBUG)
	{
		$params[':peak_mem_usage'] = memory_get_peak_usage();
		if(NLB_DEBUG)
		{
			echo "\n<!-- peak memory usage: ".$params[':peak_mem_usage']." bytes -->\n";
		}
	}

	if(NLB_LOG_PAGETIMES || NLB_DEBUG)
	{
		$PageTimer->stop();
		$mainGenTime = $PageTimer->getGenTime();
		$params[':gentime'] = $mainGenTime;
		if(NLB_DEBUG)
		{
			echo "\n<!-- generated in $mainGenTime seconds -->\n";
		}
	}
	
	if(NLB_DEBUG)
	{
		$PageTimer->start();
	}
	$query = "INSERT INTO `page_stats` (`path`,`gentime`,`referrer`,`user_agent`,`peak_mem_usage`,`datetime`) VALUES (:path,:gentime,:referrer,:user_agent,:peak_mem_usage,UTC_TIMESTAMP())";
	$DB->execUpdate($query, $params);
	if(NLB_DEBUG)
	{
		$PageTimer->stop();
		$pagetimeQueryGenTime = $PageTimer->getGenTime();
		echo "\n<!-- page_stats insert query took $pagetimeQueryGenTime seconds for a total of ".($mainGenTime+$pagetimeQueryGenTime)." seconds -->\n";
	}
}
