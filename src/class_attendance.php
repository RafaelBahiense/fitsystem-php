<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header(
    "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
);

session_start();

if (!isset($_SESSION["userID"])) {
    echo json_encode(["redirect" => "login.html"]);
    exit();
}

$method = $_SERVER["REQUEST_METHOD"];

if (!in_array($method, ["GET", "POST", "DELETE"])) {
    http_response_code(405);
    exit();
}

require_once "database/db_context.php";

try {
    $db = new DbContext();
    $connection = $db->connect();

    if ($method == "GET") {
        $classId = $_GET["classId"];

        if (empty($classId)) {
            echo json_encode(["error" => "Preencha todos os campos"]);
            http_response_code(400);
            exit();
        }

        $stmt = $connection->prepare("
          SELECT 
              c.id AS client_id,
              c.name AS client_name,
              sch.id as schedule_id,
              sch.hour,
              IF(ca.id IS NULL, 0, 1) AS attendance_status
          FROM 
              client c
          JOIN 
              class_subscription csb ON c.id = csb.client_id
          LEFT JOIN 
              class_schedule sch ON csb.class_id = sch.class_id
          LEFT JOIN 
              class_attendance ca ON c.id = ca.client_id 
              AND ca.class_id = sch.class_id 
              AND ca.class_schedule_id = sch.id 
              AND DATE(ca.attendance_date) = CURRENT_DATE()
          WHERE 
              csb.class_id = ?
              AND csb.subscription_start <= CURRENT_DATE()
              AND (csb.subscription_end IS NULL OR csb.subscription_end >= CURRENT_DATE())
              AND sch.weekday = DAYNAME(CURRENT_DATE())
          ORDER BY 
              c.name, sch.hour;
        ");
        if ($stmt === false) {
            echo json_encode(["error" => $connection->error]);
            http_response_code(500);
            exit();
        }

        $stmt->bind_param("i", $classId);
        $stmt->execute();
        $results = $stmt->get_result();

        $structured_results = [];
        foreach ($results as $row) {
            $client_id = $row["client_id"];
            $client_name = $row["client_name"];
            $scheduleId = $row["schedule_id"];
            $hour = $row["hour"];
            $attendance_status = $row["attendance_status"];

            if (!isset($structured_results[$client_id])) {
                $structured_results[$client_id] = [
                    "client_id" => $client_id,
                    "name" => $client_name,
                    "attendances" => [],
                ];
            }

            $structured_results[$client_id]["attendances"][] = [
                "hour" => $hour,
                "attendance_status" => $attendance_status,
                "schedule_id" => $scheduleId,
            ];
        }

        echo json_encode(array_values($structured_results));

        $stmt->close();
    } elseif ($method == "POST") {
        $data = json_decode(file_get_contents("php://input"), true);
        $clientId = $data["client_id"];
        $classId = $data["class_id"];
        $scheduleId = $data["schedule_id"];

        if (empty($clientId) || empty($classId) || empty($scheduleId)) {
            echo json_encode(["error" => "Preencha todos os campos"]);
            http_response_code(400);
            exit();
        }

        $stmt = $connection->prepare(
            "INSERT INTO class_attendance (client_id, class_id, class_schedule_id, attendance_date) VALUES (?, ?, ?, CURRENT_DATE())"
        );

        if ($stmt === false) {
            echo json_encode(["error" => $connection->error]);
            http_response_code(500);
            exit();
        }

        $stmt->bind_param("iii", $clientId, $classId, $scheduleId);

        if ($stmt->execute()) {
            $clientId = $connection->insert_id;
            echo json_encode(["id" => $clientId, "name" => $name]);
        } else {
            echo json_encode(["error" => $stmt->error]);
        }

        $stmt->close();
    } elseif ($method == "DELETE") {
        $data = json_decode(file_get_contents("php://input"), true);
        $clientId = $data["client_id"];
        $classId = $data["class_id"];
        $scheduleId = $data["schedule_id"];

        if (empty($clientId) || empty($classId) || empty($scheduleId)) {
            echo json_encode(["error" => "Preencha todos os campos"]);
            http_response_code(400);
            exit();
        }

        $stmt = $connection->prepare(
            "DELETE FROM class_attendance WHERE client_id = ? AND class_id = ? AND class_schedule_id = ?"
        );

        if ($stmt === false) {
            echo json_encode(["error" => $connection->error]);
            http_response_code(500);
            exit();
        }

        $stmt->bind_param("iii", $clientId, $classId, $scheduleId);

        if ($stmt->execute()) {
            $clientId = $connection->insert_id;
            echo json_encode(["id" => $clientId, "name" => $name]);
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
