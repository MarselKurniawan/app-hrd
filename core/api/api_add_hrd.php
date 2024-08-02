<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Mulai buffer output
ob_start();

// Include database connection
include_once __DIR__ . '/../../includes/database.php';

// Define the HRD class
class HRD
{
    private $conn;
    private $table_name = "users"; // Nama tabel pengguna

    public $name;
    public $email;
    public $password; // Kolom password
    public $role_id;  // Role ID untuk HRD

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        // Insert user into users table
        $query = "INSERT INTO " . $this->table_name . " (name, email, password, role_id) VALUES (:name, :email, :password, :role_id)";
        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role_id', $this->role_id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }
}

// Get the database connection
$database = new Database();
$db = $database->getConnection();
$hrd = new HRD($db);

// Get data from POST request
$data = json_decode(file_get_contents("php://input"), true); // true untuk array asosiatif

// Bersihkan output buffer
ob_end_clean();

// Header untuk JSON
header('Content-Type: application/json');

// Validate input data
if (
    !empty($data['name']) &&
    !empty($data['email']) &&
    !empty($data['password']) // Validasi password
) {
    // Set the HRD properties
    $hrd->name = $data['name'];
    $hrd->email = $data['email'];
    $hrd->password = password_hash($data['password'], PASSWORD_BCRYPT); // Enkripsi password
    $hrd->role_id = 2; // Role ID untuk HRD

    // Create the HRD record
    if ($hrd->create()) {
        echo json_encode(["message" => "HRD added successfully."]);
    } else {
        echo json_encode(["message" => "Unable to add HRD."]);
    }
} else {
    echo json_encode(["message" => "Incomplete data."]);
}
