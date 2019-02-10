<?php

  header("Content-Type: application/json");
  ini_set("session.cookie_httponly", 1);// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
	session_start();

  $json_str = file_get_contents('php://input');
  $json_obj = json_decode($json_str, true);

  require 'database.php';

  $event_id = $json_obj['event_id'];

  if(empty($_SESSION['user_id'])){ // is user not login, error, login first
    echo json_encode(array(
      "success" => false,
      "message" => "Not Log In"
    ));
    exit;
  }

  require 'database.php';
	$stmt = $mysqli->prepare("select event_title, event_date, event_location, event_tag, event_time from event where event_id = ?");
  if(!$stmt){
      echo json_encode(array(
          "success" => false,
          "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
      ));
      exit;
  }

  $stmt->bind_param("i",$event_id);
	$stmt->execute();
	$stmt->bind_result($event_title, $event_date, $event_location, $event_tag, $event_time);
  if($stmt->fetch()){ // get title, date, location, tag, time of this event
    echo json_encode(array(
      "success" => true,
      "event_title" => $event_title,
      "event_date" => $event_date,
      "event_location" => $event_location,
      "event_tag" => $event_tag,
      "event_time" => $event_time,
      "event_id" => $event_id
    ));
  } else { // show event detail fail
    echo json_encode(array(
      "success" => false,
      "message" => "Unexpected Error"
    ));
  }
  $stmt->close();
  exit;
?>
