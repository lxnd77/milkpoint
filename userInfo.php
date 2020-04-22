<?php
/**
 * Created by PhpStorm.
 * User: Rishabh Bhargava
 * Date: 12/15/2018
 * Time: 7:26 PM
 */

require_once ("userInfoManagement.php");
$userInfoM = new userInfoManagement();

if(isset($_GET['jwt']) && isset($_GET['name']) && isset($_GET['email']) && isset($_GET['phone']) ) {
    $jwt = $_GET['jwt'];
    $name = $_GET['name'];
    $email = $_GET['email'];
    $phone = $_GET['phone'];
    $userInfo = $userInfoM->setUsersNameEmailPhoneFromJWT($jwt, $name, $email, $phone);
    if ($userInfo) {
        http_response_code(201);
        echo json_encode($userInfo);
    } else {
        http_response_code(400);
        echo json_encode("");
    }

}else if(isset($_GET['jwt'])){
    $jwt = $_GET['jwt'];
    $userInfo = $userInfoM->getUsersNameEmailPhoneFromJWT($jwt);
    if($userInfo) {
        http_response_code(201);
        echo json_encode($userInfo);
    }
    else{
        http_response_code(400);
        echo json_encode("");
    }
}
else{
    die("illegal");
}