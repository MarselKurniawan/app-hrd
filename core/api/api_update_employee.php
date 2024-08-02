<?php
// Include database connection
include '../../includes/db.php';

// Check if required POST data is set
if (!isset($_POST['id']) || !isset($_POST['job_position'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

// Retrieve POST data
$id = $_POST['id'];
$job_position = $_POST['job_position'];

// Update employee data
try {
    $sql = "UPDATE employees SET job_position = :job_position WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':job_position' => $job_position, ':id' => $id]);

    echo json_encode(['success' => 'Employee updated successfully']);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => $e->getMessage()]);
}
