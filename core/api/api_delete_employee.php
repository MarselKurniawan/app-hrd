<?php
// Include database connection
include '../../includes/db.php';

// Retrieve GET data
$id = $_GET['id'];

// Prepare SQL statement to prevent SQL injection
$sql = "DELETE FROM employees WHERE id = :id";
$stmt = $pdo->prepare($sql);

// Execute the query
if ($stmt->execute(['id' => $id])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to delete employee.']);
}
