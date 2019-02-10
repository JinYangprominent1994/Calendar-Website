<?php

header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

require 'database.php';

$username = $json_obj['unchangeUsername'];
$oldPassword = $json_obj['oldPassword'];
$newPassword = $json_obj['newChangePassword'];

session_start();


if (empty($username)){ // If users do not input a username, error
  echo json_encode(array(
      "success" => false,
      "message" => "Empty Username"
    ));
    exit;
}

if (empty($oldPassword)){ // If users do not input a old password, error
  echo json_encode(array(
      "success" => false,
      "message" => "Empty Old Password"
    ));
    exit;
}

if (empty($newPassword)){ // If users do not input a new password, error
  echo json_encode(array(
      "success" => false,
      "message" => "Empty New Password"
    ));
    exit;
}

require 'database.php'; // Link to database

// select user id and user password from database
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

if(password_verify($oldPassword,$user_password)){ // Compare the submitted password to the actual password hash
    $hashed_newPassword = password_hash($newPassword,PASSWORD_DEFAULT); //Hash the new password
    $stmt = $mysqli->prepare("update user set user_password = ? where user_id = ?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
        ));
        exit;
    }
    $stmt->bind_param("ss", $hashed_newPassword, $user_id); // Save the new hashed password into database
    if($stmt->execute()){ // change password successfully
      echo json_encode(array(
          "success" => true
      ));
      exit;
    } else {
      echo json_encode(array( // change password error
          "success" => false,
          "message" => "Unexpected Error"
      ));
      exit;
    }
    $stmt->close();
	} else {
    echo json_encode(array( // verify password error
        "success" => false,
        "message" => "Verification Error"
    ));
    exit;
}
?>
