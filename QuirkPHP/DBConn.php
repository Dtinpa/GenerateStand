<?php
//This file is not intended to be uploaded to github, or be opened by anyone but the website
//admin.  Only serves to facilitate DB connections.


$config['dbhost'] = getenv("HOST");
$config['dbuser'] = getenv("USER");
$config['dbpass'] = getenv("PASS");
$config['db'] = getenv("DB");
$mysql = null;

try {
	$mysql = new PDO($config['dbhost'], $config['dbuser'], $config['dbpass']);
	$mysql->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
} catch (Exception $e) {
	echo('Connection failed: ' . $e->getMessage());
}

   
?>
