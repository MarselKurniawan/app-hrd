<?php

function get_user_properties($user_id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT property_id FROM hrd_properties WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function get_user_role($user_id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT roles.name FROM roles JOIN users ON roles.id = users.role_id WHERE users.id = :user_id');
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchColumn();
}

function is_admin($user_id)
{
    return get_user_role($user_id) === 'admin';
}

function is_hrd($user_id)
{
    return get_user_role($user_id) === 'HRD';
}

function get_employees_by_property($property_id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM employees WHERE property_id = :property_id');
    $stmt->execute(['property_id' => $property_id]);
    return $stmt->fetchAll();
}

function db_connect()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hrd";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function get_all_employees($property_id, $start, $length, $search, $orderColumn, $orderDir) {
    global $conn;

    $query = "SELECT * FROM employees WHERE property_id = ?";

    if (!empty($search)) {
        $query .= " AND (name LIKE ? OR position LIKE ?)";
    }

    $query .= " ORDER BY $orderColumn $orderDir LIMIT ?, ?";
    
    $stmt = $conn->prepare($query);

    if (!empty($search)) {
        $searchTerm = "%$search%";
        $stmt->bind_param('issii', $property_id, $searchTerm, $searchTerm, $start, $length);
    } else {
        $stmt->bind_param('iii', $property_id, $start, $length);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $employees = [];
    
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
    
    $stmt->close();

    return $employees;
}




function add_employee($data)
{
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO employees (name, age, salary, years_worked, employment_status, position, contract_document, property_id) VALUES (:name, :age, :salary, :years_worked, :employment_status, :position, :contract_document, :property_id)');
    return $stmt->execute($data);
}

function update_employee($id, $data)
{
    global $pdo;
    $stmt = $pdo->prepare('UPDATE employees SET name = :name, age = :age, salary = :salary, years_worked = :years_worked, employment_status = :employment_status, position = :position, contract_document = :contract_document WHERE id = :id');
    return $stmt->execute(array_merge($data, ['id' => $id]));
}

function delete_employee($id)
{
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM employees WHERE id = :id');
    return $stmt->execute(['id' => $id]);
}

function get_user_by_id($user_id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute(['id' => $user_id]);
    return $stmt->fetch();
}

function get_role_name($role_id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT name FROM roles WHERE id = :id');
    $stmt->execute(['id' => $role_id]);
    $role = $stmt->fetch();
    return $role ? $role['name'] : null;
}

function getHRDList()
{
    global $pdo;
    $sql = "SELECT * FROM users WHERE role_id = (SELECT id FROM roles WHERE name = 'HRD')";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPropertyList()
{
    global $pdo;
    $sql = "SELECT * FROM properties";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
