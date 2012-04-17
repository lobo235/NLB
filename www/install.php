<?php

if($_POST['do'] == 'Install')
{
	echo "Starting Install...<br />\n";
	// Try to load our config file
	if(file_exists(realpath(dirname(__FILE__).'/../config/config.inc.php')))
	{
		require(realpath(dirname(__FILE__).'/../config/config.inc.php'));
	}
	else
	{
		die("config.inc.php file was not found. Did you follow the instructions in the handbook.html document in the docs directory?");
	}
	echo "Config file found and loaded...<br />\n";

	// Load and start our PageTimerService
	class_exists('PageTimerService') || require(NLB_LIB_ROOT.'PageTimerService.class.php');
	$PageTimer = new PageTimerService();
	$PageTimer->start();

	// Load classes that will be needed during install
	class_exists('DatabaseService') || require(NLB_LIB_ROOT.'DatabaseService.class.php');
	class_exists('App') || require(NLB_LIB_ROOT.'App.class.php');
	
	$app = App::getInstance();

	// get an instance of the DatabaseService to communicate/use the database
	$DB = DatabaseService::getInstance();

	try
	{
		$DB->forceNewConnection();
	}
	catch(DatabaseServiceException $e)
	{
		// We couldn't connect to the database
		if($e->getCode() == DatabaseServiceException::SERVER_ERROR)
		{
			die($e->getMessage().". Check the NLB_MYSQL_HOST value in config.inc.php to make sure it's correct.");
		}
		elseif($e->getCode() == DatabaseServiceException::USER_ERROR)
		{
			die($e->getMessage().". Check the NLB_MYSQL_USER and NLB_MYSQL_PASS values in config.inc.php to make sure they are correct.");
		}
		elseif($e->getCode() == DatabaseServiceException::DB_ERROR)
		{
			die($e->getMessage().". Check the NLB_MYSQL_DB value in config.inc.php to make sure it is correct.");
		}
		else
		{
			die($e->getMessage());
		}
	}
	echo "Database connection established...<br />\n";

	$schema = file_get_contents(NLB_SITE_ROOT.'schema/nlb.sql');
	$queries = explode(';', $schema, -1);

	echo "Running ".count($queries)." SQL statements...<br />\n";

	$counter = 0;

	$successfulQueries = array();
	$failedQueries = array();

	foreach($queries as $query)
	{
		$query = trim($query);
		if($query != '')
		{
			$res = $DB->exec($query);
			if($res)
			{
				$successfulQueries[] = $query;
			}
			else
			{
				$failedQueries[] = $query;
			}
		}
	}

	if(count($failedQueries) > 0)
	{
		echo count($failedQueries)." SQL statements failed to run:<br />\n";
		foreach($failedQueries as $query)
		{
			echo "<pre>".$query."</pre>\n";
		}
	}
	else
	{
		echo "All SQL statements ran successfully...<br />\n";
	}
	
	echo "Creating admin user...<br >\n";
	
	class_exists('UserService') || require(NLB_LIB_ROOT.'UserService.class.php');
	
	$userService = UserService::getInstance();
	$user = $userService->newUser();
	$user->setFirstName($_POST['firstname']);
	$user->setLastName($_POST['lastname']);
	$user->setEmail($_POST['email']);
	$user->setUsername($_POST['username']);
	$user->setPassword($_POST['password']);
	$userRight = new UserRight();
	$userRight->setRight('admin user');
	$user->setUserRights(array($userRight));
	$userService->hashUserPassword($user);
	try
	{
		$user->save();
	}
	catch(DatabaseObjectException $e)
	{
		die("Could not create admin user. ".$e->getMessage());
	}

	$PageTimer->stop();
	$mainGenTime = $PageTimer->getGenTime();
	echo "Install completed in ".$mainGenTime." seconds...<br />\n";
	
	echo '<a href="'.$app->urlRoot().'">Visit the home page</a><br />'."\n";
}
else
{
?>
<html>
	<head>
		<title>NLB - Install</title>
	</head>
	<body>
		<h1>NLB Install</h1>
		<p>Use the form below to create an admin user that you can use to administer the site</p>
		<form method="post" action="install.php">
			<label for="firstname">First Name:<br />
				<input type="text" name="firstname" id="firstname" />
			</label><br />
			<label for="lastname">Last Name:<br />
				<input type="text" name="lastname" id="lastname" />
			</label><br />
			<label for="email">Email Address:<br />
				<input type="text" name="email" id="email" />
			</label><br />
			<label for="username">Username:<br />
				<input type="text" name="username" id="username" />
			</label><br />
			<label for="password">Password:<br />
				<input type="password" name="password" id="password" />
			</label><br />
			<input type="submit" name="do" value="Install" />
		</form>
	</body>
</html>
<?php
}