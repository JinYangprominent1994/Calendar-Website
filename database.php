<?php
// Required file to make connection with database

$mysqli = new mysqli('localhost', 'root', 'Tmd610203191519', 'eventmanagement');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>
