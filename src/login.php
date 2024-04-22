<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "database.php";

    $username = $_POST["username"];
    $password = $_POST["password"];

    header("Content-Type: application/json");

    try {
        $stmt = $conn->prepare(
            "SELECT * FROM admin WHERE username = :username AND password = :password"
        );
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $password);
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($tasks) > 0) {
            $_SESSION["userID"] = $tasks[0]["id"];
            setcookie("user", $username, time() + 86400, "/");
            echo json_encode(["redirect" => "/fitsystem/index.php"]);
            exit();
        } else {
            echo json_encode([
                "error" => "Invalid email or password",
                "username" => $username,
                "password" => $password,
            ]);
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
?>
