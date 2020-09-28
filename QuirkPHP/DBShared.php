<?php
//Dana Thompson
//dtdthomp54@gmail.com

//This file facilitates retrieving actions from the db.
// Dana Thompson
//This is an abstract class that holds shared queries between different tables
abstract class DBShared {
	protected $tableName;
	protected $config;
	protected $mysql;
	
	function __construct($config, $mysql) {
		$this->config = $config;
		$this->mysql = $mysql;
	}
	// Retrieves a random entry from any of the rows from a specified column
	public function getRandomRow($colName) {
		$sql = "SELECT $colName FROM $this->tableName ORDER BY RAND() LIMIT 1;";
		$getRandRow = $this->mysql->prepare($sql);
		$getRandRow->execute();
		
		if($result = $getRandRow->fetch(PDO::FETCH_ASSOC)) {
			$row = $result;
		} else {
			$row = "";
		}
		 
		return $row;
	}
	
	public function getRowId($idName, $colName, $tableName, $val) {
		$sql = "SELECT $idName FROM $tableName WHERE $colName = :val";
		$id = $this->mysql->prepare($sql);
		$id->bindValue(":val", $val);
		$id->execute();
		$result = $id->fetch(PDO::FETCH_ASSOC);
				
		if(!empty($result)) {
			return $result[$idName];
		} else {
			return $result;
		}
	}
}
?>