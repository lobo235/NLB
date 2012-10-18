<?php

class_exists('EntityService') || require_once(NLB_LIB_ROOT.'services/EntityService.class.php');
class_exists('Faq') || require_once(NLB_LIB_ROOT.'dom/Faq.class.php');

$entityService = EntityService::getInstance();
$vars['faqs'] = $entityService->getEntities('Faq');
$pageVars['title'] = 'Common Questions';
$pageVars['content'] = $UI->renderTemplate('faq-view.tpl', $vars, 6);
