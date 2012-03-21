<?php

require_once('header.php');

$test_table = 'bob';

$create_table_query = "CREATE TABLE $test_table (
`id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 64 ) NULL
) ENGINE = InnoDB";

$drop_table_query = "DROP TABLE $test_table";

$insert_query = "INSERT INTO $test_table (`name`) VALUES (?)";

$select_query = "SELECT * FROM $test_table";
$select_first_query = "SELECT * FROM $test_table WHERE `id` = ?";

if($DB->exec($create_table_query))
	echo $test_table." table created\n";
else
	echo $test_table." table not created\n";

$rows = array('Bob','Frank','Bill','Nancy');
foreach($rows as $val)
{
	$id = $DB->execUpdate($insert_query, $val);
	if($id !== FALSE)
		echo $val." was inserted with id $id\n";
	else
		echo $val." was not inserted\n";
}

$res = $DB->getSelectArray($select_query);
if($res !== FALSE)
{
	print_r($res);
}
else {
	echo "getSelectArray failed\n";
}

$res = $DB->getSelectFirst($select_first_query, 3);
if($res !== FALSE)
{
	echo "getSelectFirst: $res\n";
}
else {
	echo "getSelectFirst failed\n";
}

if($DB->exec($drop_table_query))
	echo $test_table." table dropped\n";
else
	echo $test_table." table not dropped\n";

echo NLB_SITE_ROOT."\n";
echo NLB_LIB_ROOT."\n";

require_once('footer.php');
