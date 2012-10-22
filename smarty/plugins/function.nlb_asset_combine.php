<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.nlb_user_has_role.php
 * Type:     function
 * Name:     nlb_user_has_role
 * Purpose:  Checks the user to determine if they have a specify user role
 * -------------------------------------------------------------
 */

class_exists('AssetCombiner') || require_once(NLB_LIB_ROOT.'util/AssetCombiner.class.php');
class_exists('App') || require_once(NLB_LIB_ROOT.'util/App.class.php');

function smarty_function_nlb_asset_combine(array $params, Smarty_Internal_Template $template)
{
	$app = App::getInstance();
	if(!NLB_DEBUG)
	{
		$combine = new AssetCombiner();
		$combine->setOutputDir(NLB_SITE_ROOT.'www/combined-assets');
		$notPackagedCSS = array();
		$notPackagedJS = array();
		foreach($params['files'] as $file)
		{
			if($file['package'])
			{
				if(file_exists(NLB_SITE_ROOT.'www/'.$file['filename']))
					$resource = NLB_SITE_ROOT.'www/'.$file['filename'];
				elseif(file_exists($file['filename']))
					$resource = $file['filename'];
				$combine->addFile($resource, $file['minify']);
			}
			else
			{
				$ext = pathinfo($file['filename'], PATHINFO_EXTENSION);
				if($ext == 'css')
					$notPackagedCSS[] = $file;
				elseif($ext =='js')
					$notPackagedJS[] = $file;
			}
		}
		$combine->combine();

		$output = '<link rel="stylesheet" href="'.$app->urlRoot().str_replace(NLB_SITE_ROOT.'www/', '', $combine->getCachedCSSFile()).'" />'."\n";
		foreach($notPackagedCSS as $file)
		{
			$output .= "\t\t".'<link rel="stylesheet" href="'.$app->urlRoot().$file['filename'].'" />\n';
		}
		$output .= "\t\t".'<script type="text/javascript" src="'.$app->urlRoot().str_replace(NLB_SITE_ROOT.'www/', '', $combine->getCachedJSFile()).'"></script>'."\n";
		foreach($notPackagedJS as $file)
		{
			$output .= "\t\t".'<script type="text/javascript" src="'.$app->urlRoot().$file['filename'].'"></script>'."\n";
		}
		return $output;
	}
	else
	{
		$cssoutput = '';
		$jsoutput = '';
		foreach($params['files'] as $file)
		{
			if(file_exists(NLB_SITE_ROOT.'www/'.$file['filename']))
				$resource = $app->urlRoot().$file['filename'];
			else
				$resource = $app->urlRoot().'theme_asset.php?theme='.NLB_THEME.'&f='.str_replace(NLB_SITE_ROOT.'sites/'.$app->siteFolder().'/themes/'.NLB_THEME.'/', '', $file['filename']);
			
			$ext = pathinfo($file['filename'], PATHINFO_EXTENSION);
			if($ext == 'css')
			{
				$cssoutput .= '<link rel="stylesheet" href="'.$resource.'" />'."\n";
			}
			elseif($ext == 'js')
			{
				$jsoutput .= '<script type="text/javascript" src="'.$resource.'"></script>'."\n";
			}
		}
		return $cssoutput.$jsoutput;
	}
}