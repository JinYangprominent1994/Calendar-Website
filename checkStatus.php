<?php
header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);
session_start();

$login_flag = false;

if (!empty($_SESSION['user_id']) && !empty($_SESSION['user_name']) ){
  $login_flag = true;
}

if ($login_flag) { // check whether user can login.
    echo json_encode(array(
		"success" => true,
    "user_id" => $_SESSION['user_id'],
    "user_name" => $_SESSION['user_name'],
    "token" => $_SESSION['token']
  ));
  exit;
} else {
	echo json_encode(array(
		"success" => false,
		"message" => "Log In First"
	));
	exit;
}

?>
