<?php
/**
 * Created by PhpStorm.
 * User: Rishabh Bhargava
 * Date: 12/16/2018
 * Time: 5:13 PM
 */

class productManagement
{
    private $db;
    private $jwtM;

    public function __construct()
    {
        require_once ("db_functions.php");
        require_once ("jwtManagement.php");
        $this->db = new DB_Functions();
        $this->jwtM = new jwtManagement();
    }

    public function getAllProducts($jwtToken){
        if ($this->jwtM->validateJWT($jwtToken)) {
            $products = $this->db->getAllProducts();
            return $products;
        }
        else{
            return null;
        }
    }
}