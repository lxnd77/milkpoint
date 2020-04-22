<?PHP

	require_once("db_functions.php");
	require_once("jwtManagement.php");
	$db = new DB_Functions();
	$jwtM = new jwtManagement();

	$response = array("error"=>FALSE);

	if(isset($_POST['email']) && isset($_POST['password'])){
		$email = $_POST['email'];
		$password = $_POST['password'];

		$user = $db->getUserByEmailAndPassword($email,$password);
		if($user != FALSE){
			$uid = $user["user_unique_id"];
			$jwt = $jwtM->generateNewJWT($uid);
			$response["error"] = FALSE;
			$response["user"]["name"] = $user["user_name"];
			$response["user"]["email"] = $user["user_email"];
			$response["user"]["date_created"] = $user["date_created"];
			$response["jwt"] = $jwt;
			http_response_code(200);
			echo json_encode($response);
		}
		else{
			$response["error"] = TRUE;
			$response["error_msg"] = "Login email and/or password is wrong.";
			http_response_code(400);
			echo json_encode($response);
		}
	}
	else if(isset($_POST['phone']) && isset($_POST['password'])){
		$phone = $_POST['phone'];
		$password = $_POST['password'];

		$user = $db->getUserByPhoneAndPassword($phone,$password);
		if($user != FALSE){
			$uid = $user["user_unique_id"];
			$jwt = $jwtM->generateNewJWT($uid);
			$response["error"] = FALSE;
			$response["user"]["name"] = $user["user_name"];
			$response["user"]["phone"] = $user["user_phone"];
			$response["user"]["date_created"] = $user["date_created"];
			$response["jwt"] = $jwt;
			http_response_code(200);
			echo json_encode($response);
		}
		else{
			$response["error"] = TRUE;
			$response["error_msg"] = "Login phone and/or password is wrong.";
			http_response_code(400);
			echo json_encode($response);
		}
	}
	else{
		$response["error"] = TRUE;
		$response["error_msg"] = "Wrong parameters provided.";
		http_response_code(400);
		echo json_encode($response);
	}

