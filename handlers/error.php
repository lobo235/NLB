<?php

require_once(NLB_LIB_ROOT.'AssetCombiner.class.php');

if($_GET['t'] == 'combine')
{
  $combiner = new AssetCombiner();
  $combiner->setOutputDir(NLB_SITE_ROOT.'www/combined-assets/'.md5($_SERVER['REQUEST_URI']));
  $combiner->addFile(NLB_SITE_ROOT.'www/css/nlb.css');
  $combiner->addFile(NLB_SITE_ROOT.'www/js/nlb.js');
  $combiner->combine();
}


$vars = array(
	'type' => $su->prettyFromURL($_GET['t']),
);

$UI->registerAsset('css/error.css');

$pageVars['title'] = $su->prettyFromURL($vars['type']).' Error';
$pageVars['content'] = $UI->renderTemplate('error.tpl', $vars, 3);
