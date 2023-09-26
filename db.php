<?php
/*require "db.php";*/
require_once "config.php";
$connection =mysqli_connect(
	$config['db']['server'],
	$config['db']['username'],
	$config['db']['password'],
	$config['db']['db_name']
	);
	
if ($connection == false)
{
	echo "Error DB connection</br>";
	echo mysqli_connect_error();
	exit();
}




?>
