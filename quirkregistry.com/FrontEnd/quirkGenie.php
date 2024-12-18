<?php
// Dana Thompson
// dtdthomp54@gmail.com

// This file is responsible for accumulating all the data needed to make the quirk
// then sending it back to the client for final construction

require_once '../../QuirkPHP/DBConn.php';
require_once '../../QuirkPHP/Action.php';
require_once '../../QuirkPHP/Activity.php';
require_once '../../QuirkPHP/Power.php';
require_once '../../QuirkPHP/Weak.php';
require_once '../../QuirkPHP/Body.php';

session_start();

$min_time = 30;
$max_requests = 7;

//the token sent must match the token from the page, to verify that a session from the start page was setup.
//This is used to block nefarious bots from spamming my spiders.
//Any user wishing to get my data must comply with these parameters, assuming they use the same cookie
//We don't need to worry about session high-jacking either since there are no user account
if(isset($_GET['token']) && strcmp(bin2hex($_GET['token']), $_SESSION['token'])) {
	if(!isset($_SESSION['visitCount'])) {
		$_SESSION['visitCount'] = 0;
	}
	
	//if its not already set, then set the requests 
	if(!isset($_SESSION['requests'])) {
		$_SESSION['requests'] = ['time' => time()];
	} else {
		$_SESSION['requests'][] = ['time' => time()];
	}
	$requests = $_SESSION['requests'];
	
	//For each past request, check if its been within the past 30 seconds, and mark them
	//If they're older than 30 seconds, then get rid of them, they're no longer needed
	$recentRequests = 0;
	foreach($requests as $index => $request) {
		if(!is_array($request) || $request['time'] >= time() - $min_time) {
			$recentRequests++;
		} else {
			unset($_SESSION['requests'][$index]);
		}
	}
	
	//if more than the max requests are made in a set time span, then refuse the request
	if($max_requests <= $recentRequests) {
		echo json_encode(array("error" => "Connection refused"));
		exit(0);
	}
	
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
