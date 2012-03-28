<?php
/*
require_once(NLB_LIB_ROOT.'AssetCombiner.class.php');

if($_GET['t'] == 'combine')
{
  $combiner = new AssetCombiner();
  $combiner->addFile(NLB_SITE_ROOT.'www/css/nlb.css');
  $combiner->addFile(NLB_SITE_ROOT.'www/js/nlb.js');
  $combiner->combine();
}
*/

$vars = array(
	'type' => $_GET['t'],
);

print $UI->renderTemplate('error.tpl', $vars);
