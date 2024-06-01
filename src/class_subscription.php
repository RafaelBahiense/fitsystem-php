<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header(
    "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
);

session_start();

if (!isset($_SESSION["userID"])) {
    echo json_encode(["redirect" => "login.html"]);
    exit();
}

$method = $_SERVER["REQUEST_METHOD"];

if (!in_array($method, ["POST"])) {
    http_response_code(405);
    exit();
}

require_once "database/db_context.php";

try {
    $db = new DbContext();
    $connection = $db->connect();

    $data = json_decode(file_get_contents("php://input"), true);
    $classId = intval($data["classId"]);
    $subscriptions = $data["subscriptions"];

    if (empty($classId) || empty($subscriptions)) {
        echo json_encode(["error" => "Preencha todos os campos"]);
        http_response_code(400);
        exit();
    }

    $subscriptionsToInsert = [];
    $subscriptionsToDelete = [];
    foreach ($subscriptions as $subscription) {
        if (!empty($subscription["markedForInsertion"])) {
            $subscriptionsToInsert[] = $subscription;
        } elseif (!empty($subscription["markedForDeletion"])) {
            $subscriptionsToDelete[] = $subscription;
        }
    }

    foreach ($subscriptionsToInsert as $subscription) {
        $stmt = $connection->prepare(
            "INSERT INTO class_subscription (class_id, client_id) VALUES (?, ?)"
        );

        if ($stmt === false) {
            echo json_encode([
                "error" => $connection->error,
                "elp" => $subscription,
            ]);
            http_response_code(500);
            exit();
        }

        $stmt->bind_param("ii", $classId, $subscription["client_id"]);

        if (!$stmt->execute()) {
            echo json_encode(["error" => $stmt->error]);
            http_response_code(500);
            exit();
        }

        $stmt->close();
    }

    foreach ($subscriptionsToDelete as $subscription) {
        $stmt = $connection->prepare(
            "DELETE FROM class_subscription WHERE id = ?"
        );

        if ($stmt === false) {
            echo json_encode([
                "error" => $connection->error,
            ]);
            http_response_code(500);
            exit();
        }

        $stmt->bind_param("i", $subscription["subscription_id"]);

        if (!$stmt->execute()) {
            echo json_encode(["error" => $stmt->error]);
            http_response_code(500);
            exit();
        }

        $stmt->close();
    }

    echo json_encode([
        "message" => "Inscrições da aula atualizados com sucesso",
    ]);

    $db->disconnect();
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
    http_response_code(500);
}
