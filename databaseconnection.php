<?php
class DatabaseConnection {
    public $conn;

    function __construct(){
        $DB_username = "root";
        $DB_password = "";
        $servername = "localhost";

        $this->conn = new mysqli($servername, $DB_username, $DB_password, "bombgat");
    
        if ($this->conn->connect_error){
            die($this->conn->connect_error);
        }
    }
    
}