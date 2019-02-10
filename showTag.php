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

// select all events with the specific tag
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
$result = $stmt->get_result();
ß
// persistent XSS, not escape output
// use htmlentities to prevent output escape
while($row = $result->fetch_assoc()){
      $item = [];
      $item['event_title']= htmlentities($row["event_title"]);
      $item['event_date'] = htmlentities($row["event_date"]);
      $item['event_id'] = htmlentities($row["event_id"]);
      $item['event_tag'] = htmlentities($row['event_tag']);
      array_push($eventData,$item);
      $count++;
  }
echo json_encode(array(
  "success" => true,
  "eventData" => $eventData,
  "count" => $count
));
$stmt->close();
exit;

?>
