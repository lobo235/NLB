<?php

if(isset($_POST['username']) && isset($_POST['password']))
{
	if(UserService::getInstance()->loginUser($_POST['username'], $_POST['password']))
	{
		header('Location: /');
		exit();
	}
}

?>
<form method="post" action="/processLogin">
	<label for="username">Username:</label><br />
	<input type="text" name="username" id="username" value="" /><br /><br />
	<label for="password">Password:</label><br />
	<input type="password" name="password" id="password" value="" /><br /><br />
	<input type="submit" name="s" value="Login" />
</form>