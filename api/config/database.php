<?php
class Database{
 
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "five_planner_db";
    private $username = "root";
    private $password = "";
    public $conn;
 
    // get the database connection
    public function connect(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
