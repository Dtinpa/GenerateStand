<?php
//Dana Thompson
//dtdthomp54@gmail.com

//This file facilitates retrieving acctivities from the db
require_once 'DBShared.php';

class Activity extends DBShared  {
	function __construct($config, $mysql) {
        $this->tableName = "Activity";
		$this->config = $config;
		$this->mysql = $mysql;
    }
}
?>