<?php

if(isset($_POST['username']) && isset($_POST['password']))
{
	if(UserService::getInstance()->loginUser($_POST['username'], $_POST['password']))
	{
		header('Location: /');
		exit();
	}
}

$pageVars['title'] = 'Login';
$pageVars['content'] = $UI->renderTemplate('login.tpl', NULL, 3);