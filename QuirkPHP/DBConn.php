<?php
//Contains configs for the mysql DB.  environment vars must be set manually in the apache2.conf file


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
