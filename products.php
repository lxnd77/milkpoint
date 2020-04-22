<?php
/**
 * Created by PhpStorm.
 * User: Rishabh Bhargava
 * Date: 12/18/2018
 * Time: 7:38 PM
 */
require_once ("productManagement.php");
$productM = new productManagement();

if(isset($_GET['jwt'])){
    $jwtToken = $_GET['jwt'];
    $products = $productM->getAllProducts($jwtToken);
    if($products) {
        http_response_code(201);
        echo json_encode($products);
    }
    else{
        http_response_code(400);
        echo json_encode("");
    }
}