<?php

require(realpath(dirname(__FILE__).'/../includes/header.php'));

class_exists('RequestRouterService') || require(NLB_LIB_ROOT.'services/RequestRouterService.class.php');
class_exists('UserService') || require(NLB_LIB_ROOT.'services/UserService.class.php');

$user = UserService::getInstance()->getUser();

// Prevent the user from using index.php or index to access pages
if(strpos($_SERVER['REQUEST_URI'], '/index') === 0)
{
	if(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) != '/index' && isset($_GET['q']) && trim($_GET['q']) != '')
	{
		header('Location: /'.trim($_GET['q'], '/'), true, 301);
	}
	else
	{
		header('Location: /', true, 301);
	}
	exit();
	//print '<pre>'.print_r($_SERVER, TRUE).'</pre>';
}

// Use our RequestRouter to route our request
$RequestRouter = RequestRouterService::getInstance();
$path = isset($_GET['q']) ? $_GET['q'] : 'index';

$pageVars = array();

$pageVars['app'] = $app;

$handler = $RequestRouter->routeRequest($path, $user);

include(NLB_SITE_ROOT.'handlers/'.$handler);

$UI->registerAsset('css/nlb.css');
$UI->registerAsset('css/util.css');
$UI->registerAsset('js/jquery-1.7.2.min.js');
$UI->registerAsset('js/nlb.js');

print $UI->renderTemplate('page.tpl', $pageVars);

require(NLB_SITE_ROOT.'includes/footer.php');
