<?php

require_once(NLB_SMARTY_CLASS_LOC);

class UI
{
	private static $instance;
	private static $smarty;

	private function __construct()
	{
		self::$smarty = new Smarty();
		self::$smarty->setConfigDir(NLB_SMARTY_DIR.'configs');
		self::$smarty->setTemplateDir(NLB_SMARTY_DIR.'templates');
		self::$smarty->setCompileDir(NLB_SMARTY_DIR.'templates_c');
		self::$smarty->setCacheDir(NLB_SMARTY_DIR.'cache');
		self::$smarty->addPluginsDir(NLB_SMARTY_DIR.'plugins');
	}

	// This declaration of a private __clone method helps enforce the singleton pattern
	private function __clone() { }

	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new UI();
		}
		return self::$instance;
	}

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
