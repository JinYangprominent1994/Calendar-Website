<?php

  header("Content-Type: application/json");
  ini_set("session.cookie_httponly", 1);// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
	session_start();

  $json_str = file_get_contents('php://input');
  $json_obj = json_decode($json_str, true);

  require 'database.php';

  $event_date = $json_obj['date'];
  $id = $_SESSION['user_id'];

  if(empty($_SESSION['user_id'])){ // if user not login, error
    echo json_encode(array(
      "success" => false,
      "message" => "Not Log In"
    ));
    exit;
  }

  require 'database.php';
  $count = 0;
  $eventData = [];

  // select event from database
	$stmt = $mysqli->prepare("select event_id, event_user_id, event_title, event_location, event_tag, event_time from event where event_user_id = ? and event_date = ?");
  if(!$stmt){
      echo json_encode(array(
          "success" => false,
          "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
      ));
      exit;
  }

  $stmt->bind_param("ss",$_SESSION['user_id'],$event_date);
	$stmt->execute();
	$stmt->bind_result($event_id, $event_user_id, $event_title, $event_location, $event_tag, $event_time);
  while($stmt->fetch()){
    if($event_id != null){ // select evnet successfully
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
