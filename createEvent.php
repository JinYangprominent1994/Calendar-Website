<?php
ini_set("session.cookie_httponly", 1);
header("Content-Type: application/json");// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

require 'database.php';

$event_title = $json_obj['title'];
$event_date = $json_obj['date'];
$event_tag = $json_obj['tag'];
$event_location = $json_obj['location'];
$event_time = $json_obj['time'];


session_start();
$id = $_SESSION['user_id'];

/*
if ($_SESSION['token'] != $token){ // Use token to prevent CSRF attacks
    echo json_encode(array(
        "success" => false,
        "message" => "Request forgery detected"
    ));
    exit;
}
*/
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
        "message" => "Empty Event Title"
      ));
      exit;
	}

	if (empty($event_date)){ // If users do not input a title for the event, error
		echo json_encode(array(
				"success" => false,
				"message" => "Empty Event Date"
			));
			exit;
	}

	// insert new event into database
	$stmt = $mysqli->prepare("insert into event(event_user_id,event_title,event_date,event_time,event_location,event_tag) values (?,?,?,?,?,?)");
  if(!$stmt){
      echo json_encode(array(
          "success" => false,
          "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
      ));
      exit;
  }
  $stmt->bind_param('isssss',$id,$event_title,$event_date,$event_time,$event_location,$event_tag);


  if($stmt->execute()){ // get event id of the most recent added event
    $stmtt = $mysqli -> prepare("select max(event_id) from event");
    if(!$stmtt){
        echo json_encode(array(
            "success" => false,
            "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
        ));
        exit;
    }
    $stmtt->execute();

    $stmtt->bind_result($event_id);
    $stmtt->fetch();
    $stmtt->close();

    echo json_encode(array( // create event successfully
        "success" => true,
        "event_id" => $event_id,
				"event_title" => $event_title
    ));
    exit;

  }else{
    echo json_encode(array( // create event error
        "success" => false,
        "message" => "Unexprected Error"
    ));
  }

  $stmt->close();
  exit;
?>
