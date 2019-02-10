<?php

header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);


$event_id = $json_obj['event_id'];
$token = $json_obj['deleteToken'];

session_start();

if ($_SESSION['token'] != $token){ // Use token to prevent CSRF attacks
    echo json_encode(array(
        "success" => false,
        "message" => "Request forgery detected"
    ));
    exit;
}

if(empty($_SESSION['user_id'])){ // If user visit website without login
  echo json_encode(array(
      "success" => false,
      "message" => "Log In First"
    ));
    exit;
}

if(empty($event_id)){ // // If specific event cannot be found
  echo json_encode(array(
      "success" => false,
      "message" => "Unexpected Error"
    ));
    exit;
}

require 'database.php';

// delete this event from database
$stmt = $mysqli->prepare("delete from event where event_id= ?");
if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
    ));
    exit;
}
$stmt->bind_param('i', $event_id);

if ($stmt->execute()){ // delete event successfully
  echo json_encode(array(
      "success" => true
    ));
}
else{ // delete event error
  echo json_encode(array(
      "success" => false,
      "message" => "Delete Error"
    ));
  }
$stmt->close();
exit;
?>
