<?php

header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$event_tag = $json_obj['tag'];

session_start();

if(empty($_SESSION['user_id'])){ // If user visit website without login
  echo json_encode(array(
      "success" => false,
      "message" => "Log In First"
  ));
  exit;
}

require 'database.php';
$count = 0;
$eventData = [];

// select all events with selected tag from database
$stmt = $mysqli->prepare("select event_id,event_user_id,event_date,event_title,event_location,event_time from event where event_tag=? and event_user_id = ? order by event_date desc");
if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
    ));
    exit;
}

$stmt->bind_param("si",$event_tag,$_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($event_id, $event_user_id, $event_date, $event_title, $event_location, $event_time);
while($stmt->fetch()){ // select events successfully
  if($event_id != null){
    $event = array(
      "event_id" => $event_id,
      "event_title" => $event_title,
      "event_date" => $event_date,
      "event_tag" => $event_tag
    );
    array_push($eventData, $event);
    $count ++;
  }
}

echo json_encode(array(
  "success" => true,
  "eventData" => $eventData,
  "count" => $count
));
$stmt->close();
exit;
?>
