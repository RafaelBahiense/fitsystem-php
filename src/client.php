<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header(
    "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
);

session_start();

if (!isset($_SESSION["userID"])) {
    header("Location: login.html");
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

        $stmt = $connection->prepare("SELECT * FROM client LIMIT ? OFFSET ?");
        if ($stmt === false) {
            echo json_encode(["error" => $connection->error]);
            http_response_code(500);
            exit();
        }

        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $clients = [];
        while ($row = $result->fetch_assoc()) {
            if (isset($row["date_of_birth"])) {
                $row["dateOfBirth"] = $row["date_of_birth"];
                unset($row["date_of_birth"]);
            }

            $clients[] = $row;
        }

        echo json_encode($clients);

        $stmt->close();
    } elseif ($method == "POST") {
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data["name"];
        $dateOfBirth = $data["dateOfBirth"];
        $email = $data["email"];
        $gender = $data["gender"];
        $phone = $data["phone"];
        $address = $data["address"];
        $photo = $data["photo"];

        if (
            empty($name) ||
            empty($dateOfBirth) ||
            empty($email) ||
            empty($gender) ||
            empty($phone) ||
            empty($address)
        ) {
            echo json_encode(["error" => "Preencha todos os campos"]);
            http_response_code(400);
            exit();
        }

        if (empty($photo)) {
            // Prepare the SQL statement without the photo column
            $stmt = $connection->prepare(
                "INSERT INTO client (name, date_of_birth, gender, email, phone, address) VALUES (?, ?, ?, ?, ?, ?)"
            );

            if ($stmt === false) {
                echo json_encode(["error" => $connection->error]);
                http_response_code(500);
                exit();
            }

            // Bind the parameters without the photo
            $stmt->bind_param(
                "ssssss",
                $name,
                $dateOfBirth,
                $gender,
                $email,
                $phone,
                $address
            );
        } else {
            // Prepare the SQL statement with the photo column
            $stmt = $connection->prepare(
                "INSERT INTO client (name, date_of_birth, gender, email, phone, address, photo) VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

            if ($stmt === false) {
                echo json_encode(["error" => $connection->error]);
                http_response_code(500);
                exit();
            }

            // Bind the parameters including the photo
            $stmt->bind_param(
                "sssssss",
                $name,
                $dateOfBirth,
                $gender,
                $email,
                $phone,
                $address,
                $photo
            );
        }

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
        $dateOfBirth = $data["dateOfBirth"];
        $email = $data["email"];
        $gender = $data["gender"];
        $phone = $data["phone"];
        $address = $data["address"];
        $photo = $data["photo"];

        if (
            empty($id) ||
            empty($name) ||
            empty($dateOfBirth) ||
            empty($email) ||
            empty($gender) ||
            empty($phone) ||
            empty($address)
        ) {
            // tel witch field is empty
            echo json_encode(["error" => "Preencha todos os campos"]);
            http_response_code(400);
            exit();
        }

        $stmt = $connection->prepare(
            "UPDATE client SET name = ?, date_of_birth = ?, email = ?, gender = ?, phone = ?, address = ? WHERE id = ?"
        );

        if ($stmt === false) {
            echo json_encode(["error" => $connection->error]);
            http_response_code(500);
            exit();
        }

        $stmt->bind_param(
            "ssssssi",
            $name,
            $dateOfBirth,
            $email,
            $gender,
            $phone,
            $address,
            $id
        );

        if ($stmt->execute()) {
            echo json_encode(["message" => "Client updated successfully"]);
        } else {
            echo json_encode(["error" => $stmt->error]);
            http_response_code(500);
            exit();
        }

        $stmt->close();
    } elseif ($method == "DELETE") {
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = $_DELETE["id"];

        if (empty($id)) {
            echo json_encode(["error" => "ID is required"]);
            http_response_code(400);
            exit();
        }

        $stmt = $connection->prepare("DELETE FROM client WHERE id = ?");

        if ($stmt === false) {
            echo json_encode(["error" => $connection->error]);
            http_response_code(500);
            exit();
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Client deleted successfully"]);
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
