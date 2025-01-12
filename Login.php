<?php
header("Content-Type: application/json");

require_once "databaseconnection.php";

$method = $_SERVER['REQUEST_METHOD'];

if($method == "POST" && isset($_POST["name"]) && isset($_POST["pw"]))
{
    $dbconn = new DatabaseConnection();

    $hashedPassword = hash('sha256', $_POST["pw"]);

    $stmt = $dbconn->conn->prepare("SELECT * FROM user WHERE ID = ? AND PW = ?");
    
    $stmt->bind_param("ss", $_POST["name"], $hashedPassword);

    $stmt->execute();
    $results = $stmt->get_result();

    if ($results->num_rows > 0) 
    {
        http_response_code(201);

        $row = $results->fetch_assoc();

        echo json_encode(["cookie" => $row['cookie']]);
    }
    else
    {
        echo json_encode(["message" => "Couldnt find User! PW was $hashedPassword"]);
    }
}

?>