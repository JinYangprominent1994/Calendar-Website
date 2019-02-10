<?php

header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

require 'database.php';

$username = $json_obj['username'];
$password = $json_obj['password'];

session_start();

if(empty($username)){ // If user did not input a username, he/she cannot register
  echo json_encode(array(
      "success" => false,
      "message" => "Empty Username",
  ));
  exit;
}

if(empty($password)){ // If user did not input a username, he/she cannot register
  echo json_encode(array(
      "success" => false,
      "message" => "Empty Password",
  ));
  exit;
}

if ($username && $password) { // If user input username and password
    $hashed_password = password_hash($password,PASSWORD_DEFAULT); // Hash the password and store this hashed password in database

    $stmt = $mysqli->prepare("select user_name from user");
    if(!$stmt){
  	    echo json_encode(array(
  	        "success" => false,
  	        "message" => "Error"
  	    ));
  	    exit;
  	}
    $stmt->execute();
    $stmt->bind_result($name);
    while($stmt->fetch()){
	  if($username === $name){ // If username has been used, user has to select other usernames
      echo json_encode(array(
          "success" => false,
          "message" => "Exsit username"
      ));
      exit;
    	}
    }

    // insert user name and user password into database
    $stmt = $mysqli->prepare("INSERT INTO user (user_name, user_password) VALUES (?, ?)");
    if(!$stmt){
  	    echo json_encode(array(
  	        "success" => false,
  	        "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
  	    ));
  	    exit;
  	}
    $stmt->bind_param("ss", $username, $hashed_password); // Insert username and password into database
    if ($stmt->execute()) { // If user register successfully
      echo json_encode(array(
      		"success" => true,
          "message" => "Registion Successfully",
      	));
    }else{ // register error
        echo json_encode(array(
          "success" => false,
          "message" => "Registion Error",
        ));
    }
    $stmt->close();
    exit;
 }
?>
