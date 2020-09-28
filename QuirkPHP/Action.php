<?php
//Dana Thompson
//dtdthomp54@gmail.com

//This file facilitates retrieving actions from the db
require_once 'DBShared.php';

class Action extends DBShared  {
	function __construct($config, $mysql) {
        $this->tableName = "Action";
		$this->config = $config;
		$this->mysql = $mysql;
    }
}
?>