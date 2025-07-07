<?php
	session_start();

	if(!isset($_SESSION['token'])) {
		$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
	} 
	
	$token = $_SESSION['token'];
?>
