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
    $schedules = $data["schedules"];

    if (empty($classId) || empty($schedules)) {
        echo json_encode(["error" => "Preencha todos os campos"]);
        http_response_code(400);
        exit();
    }

    $schedulesToInsert = [];
    $scheduleToDelete = [];
    foreach ($schedules as $schedule) {
        $schedule["weekday"] = trim($schedule["weekday"]);
        $scheduleId = $schedule["id"];
        $markForDeletion = $schedule["markedForDeletion"];
        if (empty($scheduleId)) {
            $schedulesToInsert[] = $schedule;
        } elseif (!empty($markForDeletion)) {
            $scheduleToDelete[] = $schedule;
        }
    }

    foreach ($schedulesToInsert as $schedule) {
        $stmt = $connection->prepare(
            "INSERT INTO class_schedule (class_id, weekday, hour) VALUES (?, ?, ?)"
        );

        if ($stmt === false) {
            echo json_encode(["error" => $connection->error]);
            http_response_code(500);
            exit();
        }

        $stmt->bind_param(
            "iss",
            $classId,
            $schedule["weekday"],
            $schedule["hour"]
        );

        if (!$stmt->execute()) {
            echo json_encode(["error" => $stmt->error]);
            http_response_code(500);
            exit();
        }

        $stmt->close();
    }

    foreach ($scheduleToDelete as $schedule) {
        $stmt = $connection->prepare("DELETE FROM class_schedule WHERE id = ?");

        if ($stmt === false) {
            echo json_encode(["error" => $connection->error]);
            http_response_code(500);
            exit();
        }

        $stmt->bind_param("i", $schedule["id"]);

        if (!$stmt->execute()) {
            echo json_encode(["error" => $stmt->error]);
            http_response_code(500);
            exit();
        }

        $stmt->close();
    }

    echo json_encode(["message" => "Horários de aula atualizados com sucesso"]);

    $db->disconnect();
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
    http_response_code(500);
}
