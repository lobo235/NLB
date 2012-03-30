<?php

$PageTimer->stop();
$mainGenTime = $PageTimer->getGenTime();
echo "\n<!-- generated in $mainGenTime seconds -->\n";

if(LOG_PAGETIMES)
{
	$PageTimer->start();
	$query = "INSERT INTO `pagetimes` (`path`,`gentime`,`referrer`,`user_agent`,`datetime`) VALUES (?,?,?,?,UTC_TIMESTAMP())";
	$params = array(
		$_SERVER['REQUEST_URI'],
		$mainGenTime,
		(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''),
		$_SERVER['HTTP_USER_AGENT'],
	);
	$DB->execUpdate($query, $params);
	$PageTimer->stop();
	$pagetimeQueryGenTime = $PageTimer->getGenTime();
	
	if(DEBUG)
	{
		echo "\n<!-- pagetimes insert query took $pagetimeQueryGenTime seconds for a total of ".($mainGenTime+$pagetimeQueryGenTime)." seconds -->\n";
	}
}
