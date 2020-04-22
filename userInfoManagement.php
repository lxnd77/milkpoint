//userInfo Management
// -> checks JWT, then gets and sets info for that specific user

<?PHP

class userInfoManagement
{

    private $db;
    private $jwtM;

    function __construct()
    {
        require_once("db_functions.php");
        require_once("jwtManagement.php");
        $this->db = new DB_Functions();
        $this->jwtM = new jwtManagement();
    }

    public function getUsersNameEmailPhoneFromJWT($jwtToken)
    {
        if ($this->jwtM->validateJWT($jwtToken)) {
            $uid = $this->jwtM->getUserIdFromJWT($jwtToken);
            $user = $this->db->getUsersInfoByUid($uid);
            $userInfo = array();
            $userInfo['name'] = $user['user_name'];
            $userInfo['email'] = $user['user_email'];
            $userInfo['phone'] = $user['user_phone'];
            return $userInfo;
        } else {
            echo("Invalid token error");
            return null;
        }
    }

    public function setUsersNameEmailPhoneFromJWT($jwtToken,$name,$email,$phone){
        if ($this->jwtM->validateJWT($jwtToken)) {
            $uid = $this->jwtM->getUserIdFromJWT($jwtToken);
            $user = $this->db->setUsersInfoByUid($uid,$name,$email,$phone);
            $userInfo = array();
            $userInfo['name'] = $user['user_name'];
            $userInfo['email'] = $user['user_email'];
            $userInfo['phone'] = $user['user_phone'];
            return $userInfo;
        } else {
            echo("Invalid token error");
            return null;
        }
    }
}

?>