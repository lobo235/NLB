<?php

class_exists('Smarty') || require(NLB_SMARTY_CLASS_LOC);
class_exists('App') || require(NLB_LIB_ROOT.'util/App.class.php');

/**
 * The UIService class is a service class that handles UI related tasks
 */
class UIService
{
	private static $instance;
	private $smarty;
	private $assets;
	private $App;

	/**
	 * The constructor for the UI class
	 * @return UIService
	 */
	private function __construct()
	{
		$this->App = App::getInstance();
		$this->smarty = new Smarty();
		$this->smarty->setConfigDir(NLB_SMARTY_DIR.'configs');
		$this->smarty->setTemplateDir(NLB_SITE_ROOT.'sites/'.$this->App->siteFolder().'/themes/'.NLB_THEME);
		$this->smarty->addTemplateDir(NLB_SMARTY_DIR.'nlb_templates', 'nlb');
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
	public function renderTemplate($template, $vars = NULL, $indentLevel = 0)
	{
		// clear all assigned variables
		$this->smarty->clearAllAssign();
		
		// Set global vars for the current page
		if(is_array($GLOBALS['pageVars']))
		{
			foreach($GLOBALS['pageVars'] as $key => $var)
			{
				$this->smarty->assign($key, $var);
			}
		}
		
		// Set vars specific to this template
		if(is_array($vars))
		{
			foreach($vars as $key => $var)
			{
				$this->smarty->assign($key, $var);
			}
		}
		
		// assign our assets
		$this->smarty->assign('assets', $this->assets);
		
		if(NLB_DEBUG)
		{
			if(file_exists(NLB_SITE_ROOT.'sites/'.$this->App->siteFolder().'/themes/'.NLB_THEME.'/'.$template))
			{
				$template_dir = NLB_SITE_ROOT.'sites/'.$this->App->siteFolder().'/themes/'.NLB_THEME.'/';
			}
			else
			{
				$template_dir = 'smarty/nlb_templates/';
			}
			return "\n\n<!-- START $template_dir$template -->\n".$this->addIndenting($this->smarty->fetch($template), $indentLevel)."\n<!-- END $template_dir$template -->\n\n";
		}
		else
		{
			return $this->addIndenting($this->smarty->fetch($template), $indentLevel);
		}
	}
	
	public function registerAsset($filename, $minify = TRUE)
	{
		$this->assets[] = array(
			'filename' => $filename,
			'minify' => $minify,
		);
	}
	
	private function addIndenting($str, $level, $indentString = "\t")
	{
		if($level == 0)
		{
			return $str;
		}
		else
		{
			$lines = preg_split("/(\n|\r|\n\r)/", $str);
			$output = array();
			foreach($lines as $line)
			{
				$output[] = str_repeat($indentString, $level).$line;
			}
			return implode("\n", $output);
		}
	}
	
	public function assignVar($name, $var)
	{
		$this->smarty->assign($name, $var);
	}
	
	public function assignVarByRef($name, &$var)
	{
		$this->smarty->assignByRef($name, $var);
	}
}
