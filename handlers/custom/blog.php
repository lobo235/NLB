<?php

if($_GET['action'] == 'viewpost' && isset($_GET['id']))
{
	echo 'Loading blog entry '.$_GET['id'];
}
elseif($_GET['action'] == 'list')
{
	echo 'Listing all blog entries';
}
