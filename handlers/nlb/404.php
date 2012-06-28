<?php

header('HTTP/1.0 404 Not Found');

$pageVars['title'] = 'Page Not Found';
$pageVars['content'] = $UI->renderTemplate('404.tpl', null, 3);
