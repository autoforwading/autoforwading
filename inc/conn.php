<?php
	$host = 'localhost';
// // 	live
// 	$username = 'u928163871_root';
// 	$password = '5yt6ku/A';
// 	$database = 'u928163871_autoforwading';

	// local
	$username = 'root';
	$password = '';
	$database = 'autoforwading';

	$db = new mysqli($host, $username, $password, $database);

	if($db->connect_error) {die('Connection failed: ' . $db->connect_error);}
?>