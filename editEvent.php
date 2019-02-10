<?php

header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$event_id = $json_obj['id'];
$event_title = $json_obj['title'];
$event_date = $json_obj['date'];
$event_location = $json_obj['location'];
$event_tag = $json_obj['tag'];
$event_time = $json_obj['time'];
$token = $json_obj['editToken'];

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

if (empty($event_title)){ // If users do not input a title for the event, error
  echo json_encode(array(
      "success" => false,
      "message" => "Empty Title"
  ));
  exit;
}

require 'database.php';

// update the detail of this event in database
  $stmt = $mysqli->prepare("update event set event_title=?, event_date=?, event_location=?, event_tag=?, event_time=? where event_id = ?");
  if(!$stmt){
      echo json_encode(array(
          "success" => false,
          "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
      ));
      exit;
  }
  $stmt->bind_param('sssssi',$event_title, $event_date,$event_location,$event_tag,$event_time,$event_id);

  if ($stmt->execute()){ // edit event successfully
    echo json_encode(array(
        "success" => true
    ));
    exit;
  } else { // edit event error
    echo json_encode(array(
        "success" => false,
        "message" => "Unexpected Error"
    ));
    exit;
  }
  $stmt->close();
  exit;
?>
