<?php

require_once(NLB_SMARTY_CLASS_LOC);

/**
 * The UIService class is a service class that handles UI related tasks
 */
class UIService
{
	private static $instance;
	private static $smarty;

	/**
	 * The constructor for the UI class
	 * @return UIService
	 */
	private function __construct()
	{
		self::$smarty = new Smarty();
		self::$smarty->setConfigDir(NLB_SMARTY_DIR.'configs');
		self::$smarty->setTemplateDir(NLB_SMARTY_DIR.'templates');
		self::$smarty->setCompileDir(NLB_SMARTY_DIR.'templates_c');
		self::$smarty->setCacheDir(NLB_SMARTY_DIR.'cache');
		self::$smarty->addPluginsDir(NLB_SMARTY_DIR.'plugins');
	}

	/**
	 * This declaration of a private __clone method helps enforce the singleton pattern
	 */
	private function __clone() { }

	/**
	 * Returns an instance of the UI class
	 * @return UIService 
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new UIService();
		}
		return self::$instance;
	}

	/**
	 * Renders the template using the provided vars and returns the output as a string
	 * @param string $template the filename of the template
	 * @param array $vars an array where keys are the variable names and values are the variable values
	 * @return string 
	 */
	public function renderTemplate($template, $vars = NULL)
	{
		// clear all assigned variables
		self::$smarty->clearAllAssign();
		if(is_array($vars))
		{
			foreach($vars as $key => $var)
			{
				self::$smarty->assign($key, $var);
			}
		}
		return self::$smarty->fetch($template);
	}
}
