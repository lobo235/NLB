<?php

require_once(NLB_SMARTY_CLASS_LOC);

/**
 * The UIService class is a service class that handles UI related tasks
 */
class UIService
{
	private static $instance;
	private $smarty;
	private $assets;

	/**
	 * The constructor for the UI class
	 * @return UIService
	 */
	private function __construct()
	{
		$this->smarty = new Smarty();
		$this->smarty->setConfigDir(NLB_SMARTY_DIR.'configs');
		$this->smarty->setTemplateDir(NLB_SMARTY_DIR.'templates');
		$this->smarty->setCompileDir(NLB_SMARTY_DIR.'templates_c');
		$this->smarty->setCacheDir(NLB_SMARTY_DIR.'cache');
		$this->smarty->addPluginsDir(NLB_SMARTY_DIR.'plugins');
		
		$this->assets = array();
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
		$this->smarty->clearAllAssign();
		if(is_array($vars))
		{
			foreach($vars as $key => $var)
			{
				$this->smarty->assign($key, $var);
			}
		}
		
		// assign our assets
		$this->smarty->assign('assets', $this->assets);
		
		return $this->smarty->fetch($template);
	}
	
	public function registerAsset($filename)
	{
		$this->assets[] = $filename;
	}
}
