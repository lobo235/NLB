<?php

/**
 * The AssetCombiner class is used to combine/minify css/js files
 */
class AssetCombiner
{

	private $files;
	private $minify;
	private $outputDir;
	private $combinedCSSFile;
	private $combinedJSFile;

	public function __construct()
	{
		$this->files = array();
		$this->minify = TRUE;
		$this->setOutputDir(realpath(dirname(__FILE__) . '/../www/combined-assets'));
	}

	public function addFile($filename)
	{
		$this->files[] = $filename;
	}

	public function setMinify($boolean)
	{
		$this->minify = $boolean;
	}

	public function setOutputDir($dir)
	{
		if(substr($dir, -1) != DIRECTORY_SEPARATOR)
		{
			$dir .= DIRECTORY_SEPARATOR;
		}
		$this->outputDir = $dir;
	}

	public function combine()
	{
		if(!$this->findExisting())
		{
			$css = '';
			$cssModifiedTimes = array();
			$js = '';
			$jsModifiedTimes = array();
			foreach($this->files as $file)
			{
				if(file_exists($file))
				{
					$ext = pathinfo($file, PATHINFO_EXTENSION);
					if($ext == 'css')
					{
						$cssModifiedTimes[] = filemtime($file);
						$css .= "\n" . file_get_contents($file);
					}
					elseif($ext == 'js')
					{
						$jsModifiedTimes[] = filemtime($file);
						$js .= "\n" . file_get_contents($file);
					}
				}
			}

			// make our directory if it doesn't exist
			if($css != '' || $js != '')
			{
				if(!is_dir($this->outputDir))
				{
					mkdir($this->outputDir, 0777, true);
					chmod($this->outputDir, 0777);
				}
			}

			if($css != '')
			{
				rsort($cssModifiedTimes);
				$this->combinedCSSFile = $this->outputDir . $this->filesHash() . $cssModifiedTimes[0] . '.cached.css';
				if($this->minify)
				{
					file_put_contents($this->combinedCSSFile, $this->cssCompress($css), LOCK_EX);
				}
				else
				{
					file_put_contents($this->combinedCSSFile, $css, LOCK_EX);
				}
				chmod($this->combinedCSSFile, 0777);
			}
			if($js != '')
			{
				rsort($jsModifiedTimes);
				$this->combinedJSFile = $this->outputDir . $this->filesHash() . $jsModifiedTimes[0] . '.cached.js';
				if($this->minify)
				{
					file_put_contents($this->combinedJSFile, $this->jsCompress($js), LOCK_EX);
				}
				else
				{
					file_put_contents($this->combinedJSFile, $js, LOCK_EX);
				}
				chmod($this->combinedJSFile, 0777);
			}
		}
	}

	public function getCachedCSSFile()
	{
		return $this->combinedCSSFile;
	}

	public function getCachedJSFile()
	{
		return $this->combinedJSFile;
	}

	private function cssCompress($buffer)
	{
		$cmd = BASH_PATH . " -c " . escapeshellarg(escapeshellcmd(YUI_COMPRESSOR_PATH) . " --type css <<< " . escapeshellarg($buffer));
		$buffer = shell_exec($cmd);
		return $buffer;
	}

	private function jsCompress($buffer)
	{
		$cmd = BASH_PATH . " -c " . escapeshellarg(escapeshellcmd(YUI_COMPRESSOR_PATH) . " --type js <<< " . escapeshellarg($buffer));
		$buffer = shell_exec($cmd);
		return $buffer;
	}

	private function findExisting()
	{
		$cssModifiedTimes = array();
		$jsModifiedTimes = array();
		foreach($this->files as $file)
		{
			if(file_exists($file))
			{
				$ext = pathinfo($file, PATHINFO_EXTENSION);
				if($ext == 'css')
				{
					$cssModifiedTimes[] = filemtime($file);
				}
				elseif($ext == 'js')
				{
					$jsModifiedTimes[] = filemtime($file);
				}
			}
		}
		
		$exists = TRUE;
		
		if(count($cssModifiedTimes) > 0)
		{
			rsort($cssModifiedTimes);
			$filename = $this->outputDir . $this->filesHash() . $cssModifiedTimes[0] . '.cached.css';
			if(file_exists($filename))
			{
				$this->combinedCSSFile = $filename;
			}
			else
			{
				$exists = FALSE;
			}
		}
		
		if(count($jsModifiedTimes) > 0)
		{
			rsort($jsModifiedTimes);
			$filename = $this->outputDir . $this->filesHash() . $jsModifiedTimes[0] . '.cached.js';
			if(file_exists($filename))
			{
				$this->combinedJSFile = $filename;
			}
			else
			{
				$exists = FALSE;
			}
		}
		return $exists;
	}
	
	private function filesHash()
	{
		return md5(implode('', $this->files)).'_';
	}
}
