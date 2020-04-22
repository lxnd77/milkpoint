<?php

require_once ("jwtManagement.php");
$jwtM = new jwtManagement();

if(isset($_GET['jwt'])){
	 $jwt = $_GET['jwt'];
	 if($jwtM->validateJWT($jwt)){
		http_response_code(201);
        // echo json_encode($userInfo);
	 }
	 else{
	 	http_response_code(400);
	 }
}
else{
	die("invalid request");
}
