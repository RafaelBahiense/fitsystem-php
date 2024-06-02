<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header(
    "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
);

session_start();

if (!isset($_SESSION["userID"])) {
    echo json_encode(["redirect" => "login.html"]);
    exit();
}

$method = $_SERVER["REQUEST_METHOD"];

if (!in_array($method, ["GET"])) {
    http_response_code(405);
    exit();
}

require_once "database/db_context.php";

try {
    $db = new DbContext();
    $connection = $db->connect();

    $stmt = $connection->prepare("
        SELECT 
            c.id AS client_id,
            c.name,
            c.photo,
            YEARWEEK(ca.attendance_date, 1) AS week,
            COUNT(ca.id) AS classes_attended
        FROM 
            client c
        LEFT JOIN 
            class_attendance ca ON c.id = ca.client_id
        WHERE 
            ca.attendance_date >= DATE_SUB(CURRENT_DATE, INTERVAL 6 WEEK)
        GROUP BY 
            c.id, c.name, YEARWEEK(ca.attendance_date, 1)
        ORDER BY 
            c.id, week;
    ");

    if ($stmt === false) {
        echo json_encode(["error" => $connection->error]);
        http_response_code(500);
        exit();
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $clients = [];
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }

    $structuredResults = [];
    foreach ($clients as $row) {
        $client_id = $row["client_id"];
        if (!isset($structuredResults[$client_id])) {
            $structuredResults[$client_id] = [
                "client_id" => $client_id,
                "name" => $row["name"],
                "photo" => $row["photo"],
                "weekly_progress" => [],
            ];
        }
        $structuredResults[$client_id]["weekly_progress"][] = [
            "week" => $row["week"],
            "classes_attended" => $row["classes_attended"],
        ];
    }

    echo json_encode(array_values($structuredResults));

    $db->disconnect();
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
    http_response_code(500);
}
