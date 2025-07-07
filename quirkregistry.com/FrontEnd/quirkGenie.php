<?php
// Dana Thompson
// dtdthomp54@gmail.com

// This file is responsible for accumulating all the data needed to make the quirk
// then sending it back to the client for final construction

require_once dirname(__FILE__) . '/../../vendor/autoload.php';
require_once '../../QuirkPHP/DBConn.php';
require_once '../../QuirkPHP/Action.php';
require_once '../../QuirkPHP/Activity.php';
require_once '../../QuirkPHP/Power.php';
require_once '../../QuirkPHP/Weak.php';
require_once '../../QuirkPHP/Body.php';

$csrf = new CSRF(7);
if($csrf->checkCSRFValid()) {
	$action = new Action($config, $mysql);
	$activity = new Activity($config, $mysql);
	$power = new Power($config, $mysql);
	$weak = new Weak($config, $mysql);
	$body = new Body($config, $mysql);

	$weakArray = $weak->getRandomWeak();
	$bodyArray = $body->getRandomRow("part");
	$powerArray = $power->getRandomPower();
	
	// splits the json string output into an array so they can be inserted
	// individually
	$result = array("action" => $action->getRandomRow("action"),
					"activity" => $activity->getRandomRow("activity"),
					"Body" => array("Part" => $bodyArray['part']),
					"Weak" => $weakArray,
					"Power" => $powerArray
					);

	echo json_encode($result);
}
?>
