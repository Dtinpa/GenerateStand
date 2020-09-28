<?php
//Dana Thompson
//dtdthomp54@gmail.com

//This file facilitates retrieving acctivities from the db
require_once 'DBShared.php';

class Body extends DBShared  {
    function __construct($config, $mysql) {
        $this->tableName = "Body";
		$this->config = $config;
		$this->mysql = $mysql;
    }

    public function insertBody($part) {
		$sql = "INSERT IGNORE INTO $this->tableName (`part`) VALUES (:part)";
		$power = $this->mysql->prepare($sql);
		$power->bindValue(":part", $part);
		$power->execute();
		
		return $this->mysql->lastInsertId();
    }
}
