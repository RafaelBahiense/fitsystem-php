<?php

require 'database.php';

$username = $_POST['username'];
$password = $_POST['password'];

try {
    $stmt = $conn->query('SELECT * FROM admin WHERE username = :username AND password = :password');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    if(count($tasks) > 0) {
        echo json_encode($tasks);
    } else {
        echo json_encode(['error' => 'Invalid email or password', 'username' => $username, 'password' => $password]);
    }
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
