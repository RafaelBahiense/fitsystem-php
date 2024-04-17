<?php

require 'database.php';

$name = $_POST['name'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$phone = $_POST['phone'];
$address = $_POST['address'];

try {
    $stmt = $conn->prepare('INSERT INTO client (name, age, gender, phone, address) VALUES (:name, :age, :gender, :phone, :address)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    $stmt->execute();
    $taskId = $conn->lastInsertId();
    echo json_encode(['id' => $taskId, 'title' => $name, 'completed' => false]);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
