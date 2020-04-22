<?PHP
require __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;

class jwtManagement
{

    private $db;
    private $secret = "milkpointSECRET1%443";

    function __construct()
    {
        require_once("db_functions.php");
        $this->db = new DB_Functions();
    }

    public function generateNewJWT($userID)
    {

        $now = time();
        $expires = $now + 14400;
        $uid = $userID;

        $token = [
            'issuedAt' => $now,
            'expires' => $expires,
            'uid' => $uid,
        ];

        // $validation = base64_encode($token); 	//Ensures NO Tampering
        // $token['validation']=validation;

        $jwt = JWT::encode(
            $token,
            $this->secret,
            'HS512'
        );

        return $jwt;
    }

    public function getUserIdFromJWT($jwtToken)
    {
        if ($this->validateJWT($jwtToken)) {
            $decoded = (array)JWT::decode($jwtToken, $this->secret, array('HS512'));
            return $decoded['userID'];
        }
        return null;
    }

    public function validateJWT($jwtToken)
    {
        try {
            $decoded = (array)JWT::decode($jwtToken, $this->secret, array('HS512'));
            $timeNow = new DateTime();

            if ($decoded['expires'] < $timeNow &&
                $decoded['issuedAt'] < $timeNow &&
                $this->db->isExistingUserId($decoded['uid'])) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo("Invalid Token");
            return false;
        }
    }
}

