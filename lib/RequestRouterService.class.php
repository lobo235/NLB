<?php

class_exists('LogService') || require(NLB_LIB_ROOT.'LogService.class.php');

/**
 * The RequestRouterService is used for routing incoming requests to the correct location
 */
class RequestRouterService {
	private static $instance;
	private $routes;
	private $Log;
	
	private function __construct()
	{
		$this->Log = LogService::getInstance();
		require(NLB_SITE_ROOT.'config/nlb_routes.inc.php');
		require(NLB_SITE_ROOT.'config/routes.inc.php');
		$this->routes = array_merge($nlb_routes, $routes);
	}
	
	/**
	 * This declaration of a private __clone method helps enforce the singleton pattern
	 */
	private function __clone() { }
	
	/**
	 * Returns an instance of the RequestRouterService class
	 * @return RequestRouterService 
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new RequestRouterService();
		}

		return self::$instance;
	}
	
	public function routeRequest($path, User $user)
	{
		if($path == '')
		{
			$path = 'index';
		}
		return $this->handleRequest($path, $user);
	}
	
	private function handleRequest($request, User $user)
	{
		$route = $this->findRoute($request);
		if($route !== FALSE)
		{
			if(strpos($route['path'], '%') !== FALSE || strpos($route['info']['handler'], '?') !== FALSE)
			{
				$this->populateHandlerVars($request, $route['path'], $route['info']['handler']);
				$route['info']['handler'] = parse_url($route['info']['handler'], PHP_URL_PATH);
			}
			
			// check user rights to this route before allowing them to access it.
			$hasRights = TRUE;
			foreach($route['info']['access'] as $right)
			{
				$hasRights = $hasRights && UserService::getInstance()->userHasRight($user, $right);
			}
			if($hasRights)
			{
				return $route['info']['handler'];
			}
			else
			{
				return 'nlb/403.php';
			}
		}
		else
		{
			return 'nlb/404.php';
		}
	}
	
	/**
	 * This method uses the given path to return a handler capable of handling the request. Will return FALSE if no handler is found
	 * @param string $path the path to use to locate a handler
	 * @return array|false the route as an array or FALSE if no handler was found
	 */
	private function findRoute($path)
	{
		// first try to match static routes
		foreach($this->routes as $key => $value)
		{
			if($path == $key && strpos($key, '%') === FALSE)
			{
				return array(
					'path' => $key,
					'info' => $value,
				);
			}
		}
		
		// now try to match dynamic routes if a static route was not found
		$pathParts = explode('/', $path);
		foreach($this->routes as $key => $value)
		{
			$routeParts = explode('/', $key);
			$routeMatches = TRUE;
			foreach($pathParts as $pathIndex => $pathPart)
			{
				if(isset($routeParts[$pathIndex]) && strpos($routeParts[$pathIndex], '%') !== FALSE)
				{
					/*$wildcards = array();
					preg_match_all('/%[a-zA-Z_]+/', $routeParts[$pathIndex], $wildcards); 
					$wildcards = $wildcards[0];
					$newRegex = $routeParts[$pathIndex];
					foreach($wildcards as $wildcard)
					{
						$newRegex = str_replace($wildcard, '(.+)', $newRegex);
					}*/

					$regex = '/^'.preg_replace('/%[a-zA-Z_]+/', '.+', $routeParts[$pathIndex]).'$/';
					$routeMatches = $routeMatches && preg_match($regex, $pathPart) == 1;
				}
				else
				{
					$routeMatches = $routeMatches && isset($routeParts[$pathIndex]) && $routeParts[$pathIndex] == $pathPart;
				}
				
				if(!$routeMatches)
				{
					break;
				}
			}
			
			if($routeMatches)
			{
				return array(
					'path' => $key,
					'info' => $value,
				);
			}
		}
		return FALSE;
	}
	
	/**
	 *
	 * @param type $request
	 * @param type $route
	 * @param type $handler
	 */
	private function populateHandlerVars($request, $route, $handler)
	{
		$requestParts = explode('/', $request);
		$routeParts = explode('/', $route);
		$queryString = parse_url($handler, PHP_URL_QUERY);
		
		foreach($routeParts as $key => $routePart)
		{
			if(strpos($routePart, '%') !== FALSE)
			{
				$replacements = array();
				$regex = '/^'.preg_replace('/%[a-zA-Z_]+/', '(.+)', $routePart).'$/';
				preg_match_all($regex, $requestParts[$key], $replacements, PREG_SET_ORDER);
				//$queryString = str_replace($routePart, $requestParts[$key], $queryString);
				$replacements = $replacements[0];
				array_shift($replacements);
				
				$wildcards = array();
				preg_match_all('/%[a-zA-Z_]+/', $routePart, $wildcards); 
				$wildcards = $wildcards[0];
				
				foreach($wildcards as $wildcardIndex => $wildcard)
				{
					$queryString = preg_replace('/'.$wildcard.'/', $replacements[$wildcardIndex], $queryString, 1);
				}
			}
		}
		
		$newVars = array();
		parse_str($queryString, $newVars);
		$_GET = array_merge($_GET, $newVars);
		$_REQUEST = array_merge($_REQUEST, $newVars);
	}
}
