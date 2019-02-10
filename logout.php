<?php

header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
session_start();

session_destroy(); // If log out, clear all session
echo json_encode(array(
  "success" => true,
  "message" => "Log Out Successfully"
));
?>
