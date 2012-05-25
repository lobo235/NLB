<?php

if(isset($_POST['username']) && isset($_POST['password']))
{
	if(UserService::getInstance()->loginUser($_POST['username'], $_POST['password']))
	{
		if(isset($_POST['login_dest']) && $_POST['login_dest'] != '')
		{
			header('Location: '.$app->urlRoot().trim($_POST['login_dest'], " \t/"));
		}
		else
		{
			header('Location: '.$app->urlRoot());
		}
		exit();
	}
}

$pageVars['title'] = 'Login';
$pageVars['login_dest'] = '';
if(isset($_REQUEST['login_dest']) && $_REQUEST['login_dest'] != '')
{
	$pageVars['login_dest'] = $_REQUEST['login_dest'];
}
$pageVars['content'] = $UI->renderTemplate('login.tpl', $pageVars, 3);