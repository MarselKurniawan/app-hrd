<?php
include_once '../../includes/functions.php';
include_once '../../includes/db.php';
session_start();

header('Content-Type: application/json');

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = db_connect();
if (!$conn) {
    die(json_encode(['error' => 'Database connection failed']));
}

$user_id = $_SESSION['user_id'] ?? null;
if (is_null($user_id)) {
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Check user's role
$stmt = $conn->prepare("SELECT role_id FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($role_id);
$stmt->fetch();
$stmt->close();

if (!$role_id) {
    echo json_encode(['error' => 'User not found']);
    exit;
}

// Fetch properties based on role
$properties = [];
$property_id = 0;

if ($role_id == 1) {
    // Admin: Fetch all properties
    $stmt = $conn->prepare("SELECT id, name FROM properties");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $properties[] = $row;
    }
    $stmt->close();
} elseif ($role_id == 2) {
    // HRD: Fetch property associated with the user
    $stmt = $conn->prepare("SELECT property_id FROM hrd_properties WHERE hrd_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($property_id);
    if (!$stmt->fetch()) {
        echo json_encode(['error' => 'No property associated with this HRD ID']);
        exit;
    }
    $stmt->close();

    // Fetch the property details
    $stmt = $conn->prepare("SELECT id, name FROM properties WHERE id = ?");
    $stmt->bind_param('i', $property_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $properties[] = $row;
    }
    $stmt->close();
} else {
    echo json_encode(['error' => 'Access restricted to specific roles']);
    exit;
}

// Fetch employees based on selected property
$employees = [];
$totalRecords = 0;

$selectedPropertyId = isset($_GET['property_id']) ? (int)$_GET['property_id'] : 0;

if ($role_id == 1 || ($role_id == 2 && $property_id > 0)) {
    $start = isset($_GET['start']) ? (int)$_GET['start'] : 0;
    $length = isset($_GET['length']) ? (int)$_GET['length'] : 10;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $orderColumn = isset($_GET['orderColumn']) ? $_GET['orderColumn'] : 'name';
    $orderDir = isset($_GET['orderDir']) ? $_GET['orderDir'] : 'asc';

    // Ensure order column is valid
    $allowedColumns = ['id', 'name', 'age', 'salary', 'years_worked', 'employment_status', 'position', 'property_id'];
    if (!in_array($orderColumn, $allowedColumns)) {
        $orderColumn = 'name';
    }

    // Ensure order direction is valid
    $orderDir = ($orderDir === 'desc') ? 'DESC' : 'ASC';

    // Query to count the total records
    if ($role_id == 1) {
        $stmt = $conn->prepare('SELECT COUNT(*) FROM employees WHERE property_id = ?');
        if ($selectedPropertyId) {
            $stmt->bind_param('i', $selectedPropertyId);
        } else {
            $stmt = $conn->prepare('SELECT COUNT(*) FROM employees');
        }
    } else {
        $stmt = $conn->prepare('SELECT COUNT(*) FROM employees WHERE property_id = ?');
        $stmt->bind_param('i', $property_id);
    }
    $stmt->execute();
    $stmt->bind_result($totalRecords);
    $stmt->fetch();
    $stmt->close();

    // Build the query dynamically for employees
    if ($role_id == 1) {
        $query = 'SELECT id, name, age, salary, years_worked, employment_status, position, property_id FROM employees WHERE 1=1';
        if ($selectedPropertyId) {
            $query .= ' AND property_id = ?';
        }
        $query .= ' ORDER BY ' . $orderColumn . ' ' . $orderDir . ' LIMIT ?, ?';
    } else {
        $query = 'SELECT id, name, age, salary, years_worked, employment_status, position, property_id FROM employees WHERE property_id = ? ORDER BY ' . $orderColumn . ' ' . $orderDir . ' LIMIT ?, ?';
    }
    $stmt = $conn->prepare($query);
    if ($role_id == 1 && $selectedPropertyId) {
        $stmt->bind_param('iii', $selectedPropertyId, $start, $length);
    } elseif ($role_id == 1) {
        $stmt->bind_param('ii', $start, $length);
    } else {
        $stmt->bind_param('iii', $property_id, $start, $length);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
    $stmt->close();
} else {
    echo json_encode(['error' => 'Access restricted']);
    exit;
}

// Output both properties and employees data
echo json_encode([
    'properties' => $properties,
    'employees' => [
        'draw' => isset($_GET['draw']) ? (int)$_GET['draw'] : 0,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $totalRecords,
        'data' => $employees,
    ]
]);
?>
