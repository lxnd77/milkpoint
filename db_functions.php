<?PHP

class DB_Functions
{

    private $connection;

    function __construct()
    {
        include_once("connection.php");
        $db = new DB_Connection();
        $this->connection = $db->connect();
    }

    function __destruct()
    {
        //TODO
    }

    public function registerUserWithEmail($name, $email, $password)
    {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"];
        $salt = $hash['salt'];
        $stmt = $this->connection->prepare(
            "INSERT INTO users(user_unique_id,user_name,user_email,user_password,user_salt,date_created) 
				VALUES (?,?,?,?,?,NOW())"
        );
        $stmt->bind_param("sssss", $uuid, $name, $email, $encrypted_password, $salt);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE user_email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $user;
        } else {
            return false;
        }
    }

    public function registerUserWithPhone($name, $phone, $password)
    {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"];
        $salt = $hash["salt"];
        $stmt = $this->connection->prepare(
            "INSERT INTO users(user_unique_id,user_name,user_phone,user_password,user_salt,date_created) 
				VALUES (?,?,?,?,?,NOW())"
        );
        $stmt->bind_param("sssss", $uuid, $name, $phone, $encrypted_password, $salt);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE user_phone = ?");
            $stmt->bind_param("s", $phone);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $user;
        } else {
            return false;
        }
    }

    public function getUserByEmailAndPassword($email, $password)
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            $salt = $user['user_salt'];
            $encrypted_password = $user['user_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            if ($encrypted_password == $hash) {
                return $user;
            }
        }
        return NULL;
    }

    public function getUserByPhoneAndPassword($phone, $password)
    {
        $stmt = $this->connection->prepare("
				SELECT * FROM users WHERE user_phone = ?"
        );
        $stmt->bind_param("s", $phone);
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            $salt = $user['user_salt'];
            $encrypted_password = $user['user_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            if ($encrypted_password == $hash) {
                return $user;
            }
        }
        return NULL;
    }

    public function isExistingUserEmail($email)
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE user_email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function isExistingUserPhone($phone)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM users WHERE user_phone=?"
        );
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function isExistingUserId($uid)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM users WHERE user_unique_id=?"
        );
        $stmt->bind_param("s", $uid);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function getUsersInfoByUid($uid)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM users WHERE user_unique_id=?"
        );
        $stmt->bind_param("s", $uid);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            $stmt->close();
            return null;
        }
    }

    public function setUsersInfoByUid($uid,$name,$email,$phone){
        $stmt = $this->connection->prepare(
            "UPDATE users SET user_name=? , user_email=? , user_phone=? WHERE user_unique_id=? "
        );
        $stmt->bind_param("ssss",$name,$email, $phone, $uid);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            $stmt->close();
            return null;
        }
    }

    public function hashSSHA($password)
    {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);

        return $hash;
    }

    public function checkhashSSHA($salt, $password)
    {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }
//TODO: FIX SQL
    public function getAllProducts()
    {
        $stmt = $this->connection->prepare(
            "SELECT DISTINCT * FROM products ORDER BY prod_name"
        );
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $products= $stmt->get_result()->fetch_all();
            $stmt->close();
            return $products;
        } else {
            $stmt->close();
            return null;
        }
    }
}


