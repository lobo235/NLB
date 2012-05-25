<form method="post" action="{$app->urlRoot()}processLogin">
	<label for="username">Username:</label><br />
	<input type="text" name="username" id="username" value="" /><br /><br />
	<label for="password">Password:</label><br />
	<input type="password" name="password" id="password" value="" /><br /><br />
	<input type="hidden" name="login_dest" value="{$login_dest}" />
	<input type="submit" name="s" value="Login" />
</form>