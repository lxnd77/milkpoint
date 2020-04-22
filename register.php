<?PHP

require_once("db_functions.php");
$db = new DB_Functions();

$response = array("error" => FALSE);

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($db->isExistingUserEmail($email)) {
        $response["error"] = TRUE;
        $response["error_msg"] = "User with email " . $email . " already exists";
        http_response_code(400);
        echo json_encode($response);
    } else {
        $user = $db->registerUserWithEmail($name, $email, $password);
        if ($user) {
            $response["error"] = FALSE;
            $response["user"]["name"] = $user["user_name"];
            $response["user"]["email"] = $user["user_email"];
            $response["user"]["date_created"] = $user["date_created"];
            http_response_code(201);
            echo json_encode($response);
        }
    }

} else if (isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['password'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    if ($db->isExistingUserPhone($phone)) {
        $response["error"] = TRUE;
        $response["error_msg"] = "User with phone number " . $phone . " already exists";
        http_response_code(400);
        echo json_encode($response);
    } else {
        $user = $db->registerUserWithPhone($name, $phone, $password);
        if ($user) {
            $response["error"] = FALSE;
            $response["user"]["name"] = $user["user_name"];
            $response["user"]["phone"] = $user["user_phone"];
            $response["user"]["date_created"] = $user["date_created"];
            http_response_code(201);
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "wrong parameters provided";
    http_response_code(500);
    echo json_encode($response);
}

