<?php
  require_once('inc.config.php');
  $email 			= $mysqli->escape_string($_GET["email"]);
  $check_like = $mysqli->query("INSERT INTO lp_emails_list
                                  (email, registration_date)
                                VALUES
                                  ('".$email."', '".time()."')");

  header("Content-type: application/json");
	echo json_encode(array("response"=>"success")); 
?>