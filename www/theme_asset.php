<?php

require_once(realpath(dirname(__FILE__).'/../includes/load_config.php'));

if(isset($_GET['theme']) && $_GET['theme'] != '' && strpos($_GET['theme'], '..') === FALSE && isset($_GET['f']) && $_GET['f'] != '' && strpos($_GET['f'], '..') === FALSE)
{
	$extmap = array(
		'css' => 'text/css',
		'js' => 'application/javascript',
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
		'png' => 'image/png',
	);
	$file = NLB_SITE_ROOT.'sites/'.$app->siteFolder().'/themes/'.NLB_THEME.'/'.$_GET['f'];
	$ext = pathinfo($file, PATHINFO_EXTENSION);
	$foundExtension = FALSE;
	foreach($extmap as $key => $value)
	{
		if($ext == $key)
		{
			header('Content-Type: '.$value);
			$foundExtension = TRUE;
			break;
		}
	}
	if(!$foundExtension)
	{
		header('Content-Type: text/plain');
	}
	
	// Caching Logic
	$fileModTime = filemtime($file);
	$headers = getRequestHeaders();
	if(isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == $fileModTime))
	{
		// send the last mod time of the file back with a 304 status code
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', $fileModTime).' GMT', true, 304);
	}
	else
	{
		// send the last mod time of the file back with a 200 status code
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', $fileModTime).' GMT', true, 200);
	}
	
	readfile($file);
}

/**
 * return the browser request header
 * use built in apache ftn when PHP built as module, or query $_SERVER when cgi
 */
function getRequestHeaders() {
	if (function_exists("apache_request_headers")) {
		if($headers = apache_request_headers()) {
			return $headers;
		}
	}
	$headers = array();
	// Grab the IF_MODIFIED_SINCE header
	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
		$headers['If-Modified-Since'] = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
	}
	return $headers;
}