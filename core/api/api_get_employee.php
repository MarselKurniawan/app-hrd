<?php
// Include database connection
include '../../includes/db.php';

// Retrieve GET data
$id = $_GET['id'];

// Fetch employee data using prepared statements to prevent SQL injection
$stmt = $pdo->prepare('SELECT * FROM employees WHERE id = :id');
$stmt->execute(['id' => $id]);
$data = $stmt->fetch();

echo json_encode($data);
