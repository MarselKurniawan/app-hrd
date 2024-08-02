<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/middleware.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hrd_id = $_POST['hrd_id'];
    $property_id = $_POST['property_id'];

    if (assignHRDToProperty($hrd_id, $property_id)) {
        echo json_encode(['status' => 'success', 'message' => 'HRD assigned to property successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to assign HRD to property']);
    }
}

function assignHRDToProperty($hrd_id, $property_id)
{
    global $pdo;
    $sql = "INSERT INTO hrd_properties (hrd_id, property_id) VALUES (:hrd_id, :property_id)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['hrd_id' => $hrd_id, 'property_id' => $property_id]);
}
