<?php

header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

require 'database.php';

$oldUsername = $json_obj['oldUsername'];
$password = $json_obj['unchangePassword'];
$newUsername = $json_obj['newChangeUsername'];

session_start();

if (empty($oldUsername)){ // If users do not input an old username, error
  echo json_encode(array(
      "success" => false,
      "message" => "Empty Old Username"
    ));
    exit;
}

if (empty($password)){ // If users do not input a password, error
  echo json_encode(array(
      "success" => false,
      "message" => "Empty Password"
    ));
    exit;
}

if (empty($newUsername)){ // If users do not input a new username, error
  echo json_encode(array(
      "success" => false,
      "message" => "Empty New Username"
    ));
    exit;
}

require 'database.php';

$stmt = $mysqli->prepare("select user_id, user_password from user where user_name = ?");
if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
    ));
    exit;
}

$stmt->bind_param('s', $oldUsername);
$stmt->execute();
$stmt->bind_result($user_id, $user_password);
$stmt->fetch();
$stmt->close();

if(password_verify($password,$user_password)){ // Compare the submitted password to the actual password hash
    $stmt = $mysqli->prepare("update user set user_name = ? where user_id = ?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
        ));
        exit;
    }

    $stmt->bind_param("si", $newUsername, $user_id);
    if($stmt->execute()){ // change username successfully
      echo json_encode(array(
          "success" => true,
      ));
      exit;
    } else {
      echo json_encode(array( // change username error
          "success" => false,
          "message" => "Unexpected Error"
      ));
      exit;
    }
    $stmt->close();
	  } else {
      echo json_encode(array( // verify password fail
          "success" => false,
          "message" => "Verification Error"
      ));
      exit;
	  }
?>
