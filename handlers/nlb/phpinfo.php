<?php
ob_start();
phpinfo();
$pageVars['title'] = 'PHP Info';
$pageVars['content'] = ob_get_clean();