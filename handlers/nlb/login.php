<?php
// Check to see if user is already logged in. If they are, route them to their dashboard
if($user->getUid() > 1)
{
	header('Location: '.$app->l('user'));
	exit();
}
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
	else
	{
		header('Location: '.$app->urlRoot().'login?failed=1');
	}
}

$pageVars['title'] = 'Login';
$pageVars['login_dest'] = $app->l('user');
if(isset($_REQUEST['login_dest']) && $_REQUEST['login_dest'] != '')
{
	$pageVars['login_dest'] = $_REQUEST['login_dest'];
}

if(isset($_REQUEST['failed']) && $_REQUEST['failed'] == 1)
{
	//echo "<pre>".print_r($_REQUEST, TRUE)."</pre>\n";
	$pageVars['error_msg'] = "Login Failed, please check your username/password and try again.";
}
else
{
	$pageVars['error_msg'] = FALSE;
}
$pageVars['content'] = $UI->renderTemplate('login.tpl', $pageVars, 3);