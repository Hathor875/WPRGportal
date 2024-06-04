<?php
class Database {
    private $servername = "localhost";
    private $username = "root";
    private $password = "1234";
    private $database = "myDB";
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function query($sql) {
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Error in SQL query: " . $this->conn->error);
        }
        return $result;
    }

    public function close() {
        $this->conn->close();
    }


}

