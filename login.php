<?php

header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

require 'database.php';

$username = $json_obj['username'];
$password = $json_obj['password'];

session_start();

if (empty($username)){ // If users do not input a username, error
  echo json_encode(array(
      "success" => false,
      "message" => "Empty Username"
    ));
    exit;
}

if (empty($password)){ // If user do not inout a password, error
  echo json_encode(array(
      "success" => false,
      "message" => "Empty Password"
    ));
    exit;
}

// select user name and user password from database
$stmt = $mysqli->prepare("select user_id, user_password from user where user_name = ?");
if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
    ));
    exit;
}

$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($user_id, $user_password);
$stmt->fetch();
$stmt->close();

if(password_verify($password,$user_password)){ // Compare the submitted password to the actual password hash
		 $_SESSION['user_id'] = $user_id; // Transfer user_id to session
		 $_SESSION['user_name'] = $username; // Transfer user_name to session
     //$_SESSION['token'] = substr(md5(rand()), 0, 10);
		 $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); // Create Token to prevent CSRF attacks

    echo json_encode(array( // password Verification successfully
      	"success" => true,
        "token" => $_SESSION['token'],
        "message" => "Log In Successfully"
      ));
		 exit;
} else { // password Verification fail
    echo json_encode(array(
      		"success" => false,
          "message" => "Incorrect Username or Password"
      	));
	 }

?>
