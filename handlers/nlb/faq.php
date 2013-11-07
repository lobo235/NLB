<?php

$GLOBALS['app']->loadClass('services', 'EntityService');
$GLOBALS['app']->loadClass('dom', 'Faq');

$entityService = EntityService::getInstance();
$vars['faqs'] = $entityService->getEntities('Faq');
$pageVars['title'] = 'Common Questions';
$pageVars['content'] = $UI->renderTemplate('faq-view.tpl', $vars, 6);
