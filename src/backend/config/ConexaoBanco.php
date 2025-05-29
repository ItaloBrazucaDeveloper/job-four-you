<?php
class DataBase
{
    private $host = "";
    private $user = "";
    private $password = "";
    private $dbname = "";
    private $port = 3306;
    public $conn;

    public function __construct()
    {
        $this->conn = new mysqli(
            hostname: $this->host,
            database: $this->dbname,
            username: $this->user,
            password: $this->password,
            port: $this->port
        );

        if ($this->conn->connect_error) {
            die("Falha na conexão com o banco de dados: {$this->conn->connect_error}");
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function closeConnection()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}