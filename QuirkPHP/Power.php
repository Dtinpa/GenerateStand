<?php
//Dana Thompson
//dtdthomp54@gmail.com

//This file facilitates retrieving acctivities from the db
require_once 'DBShared.php';

class Power extends DBShared  {
    function __construct($config, $mysql) {
        $this->tableName = "Power";
		$this->config = $config;
		$this->mysql = $mysql;
    }

    public function getRandomPower() {
		$sql = "SELECT Power.name AS Name, Power.desc AS 'Desc', Limit.limit AS 'Limit', Special.special AS 'Special' 
		FROM $this->tableName 
		INNER JOIN `Limit` ON `Limit`.power_id = $this->tableName.id
		LEFT JOIN Special ON Special.power_id = $this->tableName.id 
		ORDER BY RAND() LIMIT 1;";
		$power = $this->mysql->prepare($sql);
		$power->execute();
		if($result = $power->fetch(PDO::FETCH_ASSOC)) {
			//even if one has a special it can join to, it shouldn't automatically be applied
			$randomSpec = rand(0,101);
			if($randomSpec < 90) {
					$result['Special'] = NULL;
			}
			
			$row = $result;
		} else {
			$row = "";
		}
		
        return $row; 
    }

	//insert the power data
    public function insertPower($name, $desc, $limit, $special) {
		try {
			$this->mysql->beginTransaction();
		
			//if the description is longer than 500, we need to shorten it.
			$descFinal = $desc;
			if(strlen($desc) >= 500) {
				$desc500 = mb_substr($desc, 0, 500);
				$lastSentence = strrpos($desc500, ".");
				$descFinal = substr($desc500, 0, $lastSentence);
			}
		
			$sql = "INSERT IGNORE INTO $this->tableName (`name`, `desc`) VALUES (:name, :desc)";
			$power = $this->mysql->prepare($sql);
			$power->bindValue(":name", $name);
			$power->bindValue(":desc", $descFinal);
			$power->execute();
			
			$id = $this->mysql->lastInsertId();
			if($id == 0) {
				$powerId = $this->getRowId("id", "`name`", $this->tableName, $name);
				if(empty($powerId)) {
					throw new Exception("No power id could be found");
				}
			} else {
				$powerId = $id;
			}
			
			$lim = trim($limit, " \n");			
			if($lim != 'None' && !empty($lim)) {
				$sql2 = "INSERT IGNORE INTO `Limit` (power_id, `limit`) VALUES (:id, :limit)";
				$limit = $this->mysql->prepare($sql2);
				$limit->bindValue(":id", $powerId);
				$limit->bindValue(":limit", $lim);
				$limit->execute();
			}
			
			$spec = trim($special, " \n");	
			if($spec != 'None' && !empty($spec)) {
				$sql3 = "INSERT IGNORE INTO Special (power_id, special) VALUES (:id, :special)";
				$limit = $this->mysql->prepare($sql3);
				$limit->bindValue(":id", $powerId);
				$limit->bindValue(":special", $spec);
				$limit->execute();
			}
			
			$this->mysql->commit();
			return true;
		} catch(Exception $e) {
			$this->mysql->rollback();
			throw $e;
			return false;
		}
	}
}
