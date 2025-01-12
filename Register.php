<?php
header("Content-Type: application/json");

require_once "databaseconnection.php";

$method = $_SERVER['REQUEST_METHOD'];

if($method == "POST" && isset($_POST["name"]) && isset($_POST["pw"]))
{
    $dbconn = new DatabaseConnection();

    $name = $_POST["name"];
    $pw = $_POST["pw"];

    $stmt = $dbconn->conn->prepare("SELECT * FROM user WHERE ID = ?");
    
    $stmt->bind_param("s", $name);
    $stmt->execute();

    $results = $stmt->get_result();

    if ($results->num_rows > 0) 
    {
        http_response_code(409);
        echo json_encode(["message" => "User already exists!"]);
    } 
    else 
    {
        $hashedPassword = hash('sha256', $_POST["pw"]);

        $timeStr = hash('sha256', (string)time());

        $stmt = $dbconn->conn->prepare("INSERT INTO user (ID, PW, cookie) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $hashedPassword, $timeStr);

        if ($stmt->execute()) 
        {
            http_response_code(201);
            echo json_encode(['cookie' => $timeStr]);
        } 
        else 
        {
            http_response_code(500);
            echo json_encode(["message" => "Failed to register user."]);
        }
    }
}
else
{
    echo "Invalid Register call!";
    
    http_response_code(404);
}
?>