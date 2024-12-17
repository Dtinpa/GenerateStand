<?php
//This file is not intended to be uploaded to github, or be opened by anyone but the website
//admin.  Only serves to facilitate DB connections.


$config['dbhost'] = "mysql:dbname=;host=localhost;";
$config['dbuser'] = "";
$config['dbpass'] = "";
$config['db'] = "";
$mysql = null;

try {
	$mysql = new PDO($config['dbhost'], $config['dbuser'], $config['dbpass']);
	$mysql->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
} catch (Exception $e) {
	echo('Connection failed: ' . $e->getMessage());
}

   
?>
