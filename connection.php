<?PHP

class DB_Connection
{

    private $connection;

    public function connect()
    {
        require_once 'config.php';
        $this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if (!$this->connection) {
            echo("Connection failed, please try again later.");
        }

        echo("Success.");
        return $this->connection;

    }
}

