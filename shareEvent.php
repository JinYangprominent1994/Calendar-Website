<?php
ini_set("session.cookie_httponly", 1);
header("Content-Type: application/json");
// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

require 'database.php';

$event_title = $json_obj['title'];
$event_date = $json_obj['date'];
$event_tag = $json_obj['tag'];
$event_location = $json_obj['location'];
$event_time = $json_obj['time'];
$shareUsername = $json_obj['shareUsername'];

session_start();

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

	$stmt = $mysqli->prepare("select user_id from user where user_name = ?");
	if(!$stmt){
	    echo json_encode(array(
	        "success" => false,
	        "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
	    ));
	    exit;
	}

	$stmt->bind_param('s', $shareUsername);
	$stmt->execute();
	$stmt->bind_result($shareUser_id);
	$stmt->fetch();
	$stmt->close();

// insert new evnet for the shared username
	$stmt = $mysqli->prepare("insert into event(event_user_id,event_title,event_date,event_time,event_location,event_tag) values (?,?,?,?,?,?)");
  if(!$stmt){
      echo json_encode(array(
          "success" => false,
          "message" => printf("Query Prep Failed: %s\n", $mysqli->error)
      ));
      exit;
  }
  $stmt->bind_param('isssss',$shareUser_id,$event_title,$event_date,$event_time,$event_location,$event_tag);
  if($stmt->execute()){ // share event successfully
  echo json_encode(array(
      "success" => true
  ));
  exit;

} else { // share event fail
  echo json_encode(array(
      "success" => false,
      "message" => "Unexprected Error"
  ));
}

$stmt->close();
exit;
?>
