<?php

require_once "config.php";

class DbContext
{
    private $host;
    private $port;
    private $dbname;
    private $user;
    private $password;

    private $connection;

    public function __construct()
    {
        $this->host = MYSQL_DB_HOST;
        $this->port = MYSQL_DB_PORT;
        $this->dbname = MYSQL_DB_DATABASE;
        $this->user = MYSQL_DB_USERNAME;
        $this->password = MYSQL_DB_PASSWORD;
    }

    public function connect()
    {
        $this->connection = new mysqli(
            $this->host,
            $this->user,
            $this->password,
            $this->dbname,
            $this->port
        );

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }

        return $this->connection;
    }

    public function disconnect()
    {
        $this->connection->close();
    }

    private function run_query($query)
    {
        $result = $this->connection->query($query);

        if (!$result) {
            $error = ["error" => $this->connection->error];
            return json_encode($error);
        }

        if ($result->num_rows > 0) {
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return json_encode($rows);
        }

        return json_encode($result);
    }
}
