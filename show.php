<?php

  header("Content-Type: application/json");
  ini_set("session.cookie_httponly", 1);// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
	session_start();

  $json_str = file_get_contents('php://input');
  $json_obj = json_decode($json_str, true);

  require 'database.php';

  $event_date = $json_obj['date'];
  $id = $_SESSION['user_id'];

  if(empty($_SESSION['user_id'])){ // if user doesn't log in, error
    echo json_encode(array(
      "success" => false,
      "message" => "Not Log In"
    ));
    exit;
  }

  require 'database.php';
  $count = 0;
  $eventData = [];

  // select event_id,event_user_id, event_title, event_location, event_tag, event_time from database
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

  // persistent XSS, not escape output
  // use htmlentities to prevent output escape
  $result = $stmt->get_result();
  while($row = $result->fetch_assoc()){
        $item = [];
    		$item['event_title']= htmlentities($row["event_title"]);
    		$item['event_date'] = htmlentities($row["event_date"]);
    		$item['event_id'] = htmlentities($row["event_id"]);
        $item['event_tag'] = htmlentities($row['event_tag']);
        array_push($eventData,$item);
        $count++;
    }
  echo json_encode(array( // select event successfully
    "success" => true,
    "eventData" => $eventData,
    "count" => $count
  ));
  $stmt->close();
  exit;
?>
