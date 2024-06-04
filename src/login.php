<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header(
    "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
);

session_start();

$method = $_SERVER["REQUEST_METHOD"];

if (!in_array($method, ["POST"])) {
    http_response_code(405);
    exit();
}

require_once "database/db_context.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new DbContext();
    $connection = $db->connect();

    $data = json_decode(file_get_contents("php://input"), true);
    $username = $data["username"];
    $password = $data["password"];

    try {
        $stmt = $connection->prepare(
            "SELECT * FROM admin WHERE username = ? AND password = ?"
        );

        if ($stmt === false) {
            echo json_encode(["error" => $connection->error]);
            http_response_code(500);
            exit();
        }

        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        $user = $result->fetch_assoc();

        if (!empty($user)) {
            $_SESSION["userID"] = $user["id"];
            setcookie("user", $username, time() + 86400, "/");
            echo json_encode([
                "redirect" => "/fitsystem/index.php",
            ]);
            exit();
        } else {
            echo json_encode([
                "error" => "Usuário ou senha inválidos",
                "username" => $username,
                "password" => $password,
            ]);
            http_response_code(401);
            exit();
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
    exit();
}
