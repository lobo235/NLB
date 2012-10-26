<?php

// header.php figures out which config.inc.php file to load and loads it. Also instantiates all the services and objects we need to serve a page to the user.
require(realpath(dirname(__FILE__).'/../includes/header.php'));

class_exists('RequestRouterService') || require_once(NLB_LIB_ROOT.'services/RequestRouterService.class.php');
class_exists('UserService') || require_once(NLB_LIB_ROOT.'services/UserService.class.php');

$userService = UserService::getInstance();

// Try to get our logged-in user if there is one in the session, otherwise, we'll end up with the anonymous user (uid 1)
$user = $userService->getUser();

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
}

// Use our RequestRouter to route our request
$RequestRouter = RequestRouterService::getInstance();
$path = isset($_GET['q']) ? $_GET['q'] : 'index';

// Assign some variables to be used in the template system
$pageVars = array();
$pageVars['app'] = $app;
$pageVars['user'] = $user;
$pageVars['userService'] = $userService;
$pageVars['is_front'] = FALSE;

// Register the NLB default css and js files
$UI->registerAsset('css/nlb.css');
$UI->registerAsset('js/jquery-1.7.2.min.js');
$UI->registerAsset('js/nlb.js');

// Register the css and js files from the currently configured theme (uses NLB_THEME which is defined in config.inc.php)
$UI->registerThemeAssets();

// Find the handler for this request
$handler = $RequestRouter->routeRequest($path, $user);

// First look for the handler in the site specific handlers
if(file_exists(NLB_SITE_ROOT.'sites/'.$app->siteFolder().'/handlers/'.$handler['handler']))
{
	include(NLB_SITE_ROOT.'sites/'.$app->siteFolder().'/handlers/'.$handler['handler']);
}
// Then look for the handler in the default NLB handlers
else
{
	include(NLB_SITE_ROOT.'handlers/'.$handler['handler']);
}

// Print our page
print $UI->renderTemplate('page.tpl', $pageVars);

// footer.php wraps up the page request by potentially logging debug information, page generation times, and memory usage
require(NLB_SITE_ROOT.'includes/footer.php');
