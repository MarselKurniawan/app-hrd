<?php
require_once __DIR__ . '/../includes/db.php'; // Database connection
require_once __DIR__ . '/../includes/functions.php'; // Include necessary functions

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (empty($_POST['name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Property name is required']);
    exit;
}

$name = htmlspecialchars(trim($_POST['name']));

try {
    $db = db_connect();
    $sql = "INSERT INTO properties (name) VALUES (?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $name);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['success' => 'Property added successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error executing statement']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} finally {
    $stmt->close();
    $db->close();
}
