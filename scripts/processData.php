<?php

require_once dirname(__FILE__) . '/../QuirkPHP/DBConn.php';
require_once dirname(__FILE__) . '/../QuirkPHP/Body.php';
require_once dirname(__FILE__) . '/../QuirkPHP/Power.php';
require_once dirname(__FILE__) . '/../QuirkPHP/Weak.php';

$body = new Body($config, $mysql);
$power = new Power($config, $mysql);
$weak = new Weak($config, $mysql);

try {
    $handle = fopen(dirname(__FILE__) . '/powerInfo.txt', 'r');
    
    if($handle) {
		while(($line = fgets($handle)) !== false) {
			$lineDec = json_decode($line, true);
			if(isset($lineDec['Body'])) {
				$body->insertBody($lineDec['Body']);
			} elseif(isset($lineDec['Power'])) {
				$power->insertPower($lineDec['Power']['Name'], $lineDec['Power']['Desc'], $lineDec['Power']['Limit'], $lineDec['Power']['Special']);
			} elseif(isset($lineDec['Weak'])) {
				if($lineDec['Weak'] != 'None') {
					$weak->insertWeak($lineDec['Weak']['Desc'], $lineDec['Weak']['URL']);
				}
			}
        }
    }
} catch (Exception $e) {
	echo($e->getMessage());
    //do nothing
}

?>
