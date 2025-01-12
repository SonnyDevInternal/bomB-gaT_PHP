<?php
header("Content-Type: application/json");

require_once "../databaseconnection.php";

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

switch($method)
{
    case "GET":
    if (preg_match("/\/Api\/User.php/", $uri, $matches))
    {
        if (isset($_GET['cookie'])) 
        {
            $cookie = $_GET['cookie'];
            
            unset($_GET['cookie']);

            GetUser($cookie);
        } 
        else 
        {
            http_response_code(403);
            echo json_encode(["message" => "Invalid cookie"]);
        }
    }
    else
    {
        http_response_code(403);
        echo json_encode(["message" => "Invalid request!"]);
    }
    break;

    default:
        http_response_code(403);
        echo json_encode(["message" => "Invalid method!"]);
    break;
}


function GetUser($cookie)
{
    $dbconn = new DatabaseConnection();

    $sql = "SELECT ID FROM user WHERE cookie = ?";

    $stmt = $dbconn->conn->prepare($sql);
    $stmt->bind_param("s", $cookie);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) 
    {
        $data = $result->fetch_assoc();

        unset($data["PW"]);

        http_response_code(200);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    else
    {
        http_response_code(404);
        echo json_encode(["message" => "User not found"]);
    }
}
?>