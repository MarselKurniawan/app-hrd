<?php
// Include database connection
include '../../includes/db.php';

// Retrieve POST data
$id = $_POST['employee_id'];
$name = $_POST['name'];
$age = $_POST['age'];
$salary = $_POST['salary'];
$years_worked = $_POST['years_worked'];
$employment_status = $_POST['employment_status'];
$position = $_POST['position'];
$contract_document = $_FILES['contract_document']['name'];

// Move uploaded file to a secure directory if present
if ($contract_document) {
    move_uploaded_file($_FILES['contract_document']['tmp_name'], "../../uploads/$contract_document");
}

// Prepare SQL statement with placeholders
if ($contract_document) {
    $sql = "UPDATE employees SET name = :name, age = :age, salary = :salary, years_worked = :years_worked, employment_status = :employment_status, position = :position, contract_document = :contract_document WHERE id = :id";
} else {
    $sql = "UPDATE employees SET name = :name, age = :age, salary = :salary, years_worked = :years_worked, employment_status = :employment_status, position = :position WHERE id = :id";
}

$stmt = $pdo->prepare($sql);

// Bind parameters
$params = [
    ':id' => $id,
    ':name' => $name,
    ':age' => $age,
    ':salary' => $salary,
    ':years_worked' => $years_worked,
    ':employment_status' => $employment_status,
    ':position' => $position,
];

if ($contract_document) {
    $params[':contract_document'] = $contract_document;
}

// Execute the query
if ($stmt->execute($params)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update employee.']);
}
