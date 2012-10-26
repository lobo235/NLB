<?php

/**
 * The AssetCombiner class is used to combine/minify css/js files
 */
class AssetCombiner
{

	private $files;
	private $outputDir;
	private $combinedCSSFile;
	private $combinedJSFile;

	public function __construct()
	{
		$this->files = array();
		$this->minify = TRUE;
		$this->setOutputDir(realpath(dirname(__FILE__) . '/../www/combined-assets'));
	}

	public function addFile($filename, $minify = TRUE)
	{
		$this->files[] = array(
			'filename' => $filename,
			'minify' => $minify,
		);
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
			$cssFiles = array();
			$cssModifiedTimes = array();
			$jsFiles = array();
			$jsModifiedTimes = array();
			foreach($this->files as $file)
			{
				if(file_exists($file['filename']))
				{
					$ext = pathinfo($file['filename'], PATHINFO_EXTENSION);
					if($ext == 'css')
					{
						$cssModifiedTimes[] = filemtime($file['filename']);
						$cssFiles[] = $file;
					}
					elseif($ext == 'js')
					{
						$jsModifiedTimes[] = filemtime($file['filename']);
						$jsFiles[] = $file;
					}
				}
			}

			// make our directory if it doesn't exist
			if(count($cssFiles) > 0 || count($jsFiles) > 0)
			{
				if(!is_dir($this->outputDir))
				{
					mkdir($this->outputDir, 0777, true);
					chmod($this->outputDir, 0777);
				}
			}

			if(count($cssFiles) > 0)
			{
				rsort($cssModifiedTimes);
				$this->combinedCSSFile = $this->outputDir . $this->filesHash() . $cssModifiedTimes[0] . '.cached.css';
				$fileOutput = array();
				$imports = array();
				foreach($cssFiles as $file)
				{
					$fileContents = file_get_contents($file['filename']);
					$bom = pack("CCC", 0xef, 0xbb, 0xbf);
					if(0 == strncmp($fileContents, $bom, 3)) {
						$fileContents = substr($fileContents, 3);
					}
					
					$matches = null;
					$num_matches = preg_match_all('(@import.*?;)', $fileContents, $matches, PREG_SET_ORDER);
					if($num_matches > 0)
					{
						foreach($matches as $match)
						{
							$imports[] = $match[0];
						}
						$fileContents = preg_replace('(@import.*?;)', '', $fileContents);
					}
					
					$fileOutput[] = "/* ".$file['filename']." */\n".($file['minify'] ? $this->cssCompress($fileContents) : $fileContents);
				}
				array_unshift($fileOutput, implode('', $imports));
				file_put_contents($this->combinedCSSFile, implode("\n\n", $fileOutput), LOCK_EX);
				chmod($this->combinedCSSFile, 0777);
			}
			if(count($jsFiles) > 0)
			{
				rsort($jsModifiedTimes);
				$this->combinedJSFile = $this->outputDir . $this->filesHash() . $jsModifiedTimes[0] . '.cached.js';
				$fileOutput = array();
				foreach($jsFiles as $file)
				{
					$fileContents = file_get_contents($file['filename']);
					$bom = pack("CCC", 0xef, 0xbb, 0xbf);
					if(0 == strncmp($fileContents, $bom, 3)) {
						$fileContents = substr($fileContents, 3);
					}
					$fileOutput[] = "/* ".$file['filename']." */\n".($file['minify'] ? $this->jsCompress($fileContents) : $fileContents);
				}
				file_put_contents($this->combinedJSFile, implode("\n\n", $fileOutput), LOCK_EX);
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
		$cmd = NLB_BASH_PATH . " -c " . escapeshellarg(escapeshellcmd(NLB_YUI_COMPRESSOR_PATH) . " --type css <<< " . escapeshellarg($buffer));
		$buffer = shell_exec($cmd);
		return $buffer;
	}

	private function jsCompress($buffer)
	{
		$cmd = NLB_BASH_PATH . " -c " . escapeshellarg(escapeshellcmd(NLB_YUI_COMPRESSOR_PATH) . " --type js <<< " . escapeshellarg($buffer));
		$buffer = shell_exec($cmd);
		return $buffer;
	}

	private function findExisting()
	{
		$cssModifiedTimes = array();
		$jsModifiedTimes = array();
		foreach($this->files as $file)
		{
			if(file_exists($file['filename']))
			{
				$ext = pathinfo($file['filename'], PATHINFO_EXTENSION);
				if($ext == 'css')
				{
					$cssModifiedTimes[] = filemtime($file['filename']);
				}
				elseif($ext == 'js')
				{
					$jsModifiedTimes[] = filemtime($file['filename']);
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
		$filenameString = '';
		foreach($this->files as $file)
			$filenameString .= $file['filename'];
		return md5($filenameString).'_';
	}
}
