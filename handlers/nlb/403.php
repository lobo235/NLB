<?php

header('HTTP/1.0 403 Forbidden');

$pageVars['title'] = 'Access Denied';
$pageVars['content'] = $UI->renderTemplate('403.tpl', null, 3);