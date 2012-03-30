<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.asset_combine.php
 * Type:     function
 * Name:     asset_combine
 * Purpose:  Uses the AssetCombiner class to combine/aggregate css/js files
 * -------------------------------------------------------------
 */

class_exists('AssetCombiner') || require(NLB_LIB_ROOT.'AssetCombiner.class.php');

function smarty_function_asset_combine(array $params, Smarty_Internal_Template $template)
{
	if(!DEBUG)
	{
		$combine = new AssetCombiner();
		$combine->setOutputDir(NLB_SITE_ROOT.'www/combined-assets');
		foreach($params['files'] as $file)
		{
			$combine->addFile(NLB_SITE_ROOT.'www/'.$file);
		}
		$combine->setMinify(TRUE);
		$combine->combine();

		$output = '<link rel="stylesheet" href="/'.str_replace(NLB_SITE_ROOT.'www/', '', $combine->getCachedCSSFile()).'" />';
		$output .= "\n\t\t".'<script type="text/javascript" src="/'.str_replace(NLB_SITE_ROOT.'www/', '', $combine->getCachedJSFile()).'"></script>'."\n";
		return $output;
	}
	else
	{
		$cssoutput = '';
		$jsoutput = '';
		foreach($params['files'] as $file)
		{
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			if($ext == 'css')
			{
				$cssoutput .= '<link rel="stylesheet" href="/'.$file.'" />'."\n";
			}
			elseif($ext == 'js')
			{
				$jsoutput .= '<script type="text/javascript" src="/'.$file.'"></script>'."\n";
			}
		}
		return $cssoutput.$jsoutput;
	}
}