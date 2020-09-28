<?php
//Dana Thompson
//dtdthomp54@gmail.com

//This file facilitates retrieving weaknesses from the db
require_once 'DBShared.php';

class Weak extends DBShared  {
    function __construct($config, $mysql) {
        $this->tableName = "Weak";
		$this->config = $config;
		$this->mysql = $mysql;
    }
	
	public function getRandomWeak() {
		$sql = "SELECT `desc` AS 'Desc', `url` AS 'URL' FROM $this->tableName
		WHERE Weak.desc NOT LIKE '%Wuhan%' AND Weak.desc NOT LIKE '%covid%'
		ORDER BY RAND() LIMIT 1";
		$weak = $this->mysql->prepare($sql);
		$weak->execute();
		if($result = $weak->fetch(PDO::FETCH_ASSOC)) {
			$row = $result;
		} else {
			$row = "";
		}
		
        return $row; 
	}

    public function insertWeak($desc, $url) {
        $sql = "INSERT IGNORE INTO $this->tableName (`desc`, `url`) VALUES (:desc, :url)";
		$power = $this->mysql->prepare($sql);
		$power->bindValue(":desc", $desc);
		$power->bindValue(":url", $url);
		$power->execute();
		
		return $this->mysql->lastInsertId();
    }
}
