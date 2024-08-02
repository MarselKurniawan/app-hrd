<?php
include_once '../../includes/functions.php';
session_start();

header('Content-Type: application/json');

// Database connection
$conn = db_connect();
if (!$conn) {
    die(json_encode(['error' => 'Database connection failed']));
}

$user_id = $_SESSION['user_id'] ?? null;
$role_id = $_SESSION['role_id'] ?? null;

if (is_null($user_id) || is_null($role_id)) {
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

$data = [];
if ($role_id == 1) {
    // Admin: Fetch all properties
    $query = "SELECT id, name FROM properties";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else if ($role_id == 2) {
    // Regular user: Fetch properties linked to this user
    $stmt = $conn->prepare("SELECT p.id, p.name FROM properties p JOIN hrd_properties hp ON p.id = hp.property_id WHERE hp.hrd_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
}

echo json_encode($data);
