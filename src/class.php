<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header(
    "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
);

session_start();

if (!isset($_SESSION["userID"])) {
    echo json_encode(["redirect" => "login.html"]);
    exit();
}

$method = $_SERVER["REQUEST_METHOD"];

if (!in_array($method, ["POST", "PUT", "DELETE", "GET"])) {
    http_response_code(405);
    exit();
}

require_once "database/db_context.php";

try {
    $db = new DbContext();
    $connection = $db->connect();

    if ($method == "GET") {
        $page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
        $limit = isset($_GET["limit"]) ? (int) $_GET["limit"] : 10;
        $offset = ($page - 1) * $limit;

        $stmt = $connection->prepare("SELECT * FROM class LIMIT ? OFFSET ?");
        if ($stmt === false) {
            echo json_encode(["error" => $connection->error]);
            http_response_code(500);
            exit();
        }

        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $classes = [];
        while ($row = $result->fetch_assoc()) {
            $classes[] = $row;
        }

        echo json_encode($classes);

        $stmt->close();
    } elseif ($method == "POST") {
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data["name"];
        $icon = $data["icon"];
        $description = $data["description"];
        $status = $data["status"];

        if (
            empty($name) ||
            empty($icon) ||
            empty($description) ||
            empty($status)
        ) {
            echo json_encode(["error" => "Preencha todos os campos"]);
            http_response_code(400);
            exit();
        }

        $stmt = $connection->prepare(
            "INSERT INTO class (name, icon, description, status) VALUES (?, ?, ?, ?)"
        );

        if ($stmt === false) {
            echo json_encode(["error" => $connection->error]);
            http_response_code(500);
            exit();
        }

        $stmt->bind_param("ssss", $name, $icon, $description, $status);

        if ($stmt->execute()) {
            $clientId = $connection->insert_id;
            echo json_encode(["id" => $clientId, "name" => $name]);
        } else {
            echo json_encode(["error" => $stmt->error]);
        }

        $stmt->close();
    } elseif ($method == "PUT") {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data)) {
            echo json_encode(["error" => "Preencha todos os campos"]);
            http_response_code(400);
            exit();
        }

        $id = $data["id"];
        $name = $data["name"];
        $icon = $data["icon"];
        $description = $data["description"];
        $status = $data["status"];

        if (
            empty($id) ||
            empty($name) ||
            empty($icon) ||
            empty($description) ||
            empty($status)
        ) {
            // tel witch field is empty
            echo json_encode(["error" => "Preencha todos os campos"]);
            http_response_code(400);
            exit();
        }

        $stmt = $connection->prepare(
            "UPDATE class SET name = ?, icon = ?, description = ?, status = ? WHERE id = ?"
        );

        if ($stmt === false) {
            echo json_encode(["error" => $connection->error]);
            http_response_code(500);
            exit();
        }

        $stmt->bind_param("sssii", $name, $icon, $description, $status, $id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Class updated successfully"]);
        } else {
            echo json_encode(["error" => $stmt->error]);
            http_response_code(500);
            exit();
        }

        $stmt->close();
    } elseif ($method == "DELETE") {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data["id"];

        if (empty($id)) {
            echo json_encode(["error" => "ID is required"]);
            http_response_code(400);
            exit();
        }

        $stmt = $connection->prepare("DELETE FROM class WHERE id = ?");

        if ($stmt === false) {
            echo json_encode(["error" => $connection->error]);
            http_response_code(500);
            exit();
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Class deleted successfully"]);
        } else {
            echo json_encode(["error" => $stmt->error]);
        }

        $stmt->close();
    }

    $db->disconnect();
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
    http_response_code(500);
}