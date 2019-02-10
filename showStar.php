<?php

  header("Content-Type: application/json");
  ini_set("session.cookie_httponly", 1);// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
	session_start();

  $json_str = file_get_contents('php://input');
  $json_obj = json_decode($json_str, true);

  require 'database.php';

  $star_date = $json_obj['date_number'];
  $id = $_SESSION['user_id'];

  if(empty($_SESSION['user_id'])){
    echo json_encode(array(
      "success" => false,
      "message" => "Not Log In"
    ));
    exit;
  }

  require 'database.php';
  $count = 0;
  $eventData = [];
  // save this date into database
	$stmt = $mysqli->prepare("select date_number from date where date_user_id = ?");
  if(!$stmt){
      echo json_encode(array(
          "success" => false,
          "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
      ));
      exit;
  }

  $stmt->bind_param("s",$id);
	$stmt->execute();
	$stmt->bind_result($date_number);
  while($stmt->fetch()){
    if($event_id != null){
      $event = array(
        "date_number" => $date_number
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
