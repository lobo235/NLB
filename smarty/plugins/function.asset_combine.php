<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.eightball.php
 * Type:     function
 * Name:     eightball
 * Purpose:  outputs a random magic answer
 * -------------------------------------------------------------
 */

require_once(NLB_LIB_ROOT.'AssetCombiner.class.php');

function smarty_function_asset_combine(array $params, Smarty_Internal_Template $template)
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