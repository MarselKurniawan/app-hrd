<?php
// Mulai session
session_start();

// Database connection
require '../../includes/db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu.']);
    exit;
}

// Ambil ID pengguna dari session
$user_id = $_SESSION['user_id'];

// Periksa role pengguna dan ambil property_id jika role = HRD (2)
$sqlRole = "SELECT users.role_id, hrd_properties.property_id
            FROM users
            LEFT JOIN hrd_properties ON users.id = hrd_properties.hrd_id
            WHERE users.id = ?";
$stmtRole = $pdo->prepare($sqlRole);
$stmtRole->execute([$user_id]);
$userData = $stmtRole->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    echo json_encode(['success' => false, 'message' => 'User tidak ditemukan atau tidak memiliki role yang valid.']);
    exit;
}

$role_id = $userData['role_id'];
$property_id = $role_id == 2 ? $userData['property_id'] : (isset($_POST['property_id']) ? $_POST['property_id'] : '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi dan ambil data form
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $age = isset($_POST['age']) ? $_POST['age'] : '';
    $salary = isset($_POST['salary']) ? $_POST['salary'] : '';
    $years_worked = isset($_POST['years_worked']) ? $_POST['years_worked'] : ''; // Asumsi ini digunakan untuk file
    $status = isset($_POST['employment_status']) ? $_POST['employment_status'] : '';
    $position = isset($_POST['position']) ? $_POST['position'] : '';

    // Periksa jika user adalah admin (role_id = 1) dan ambil property_id dari form
    if ($role_id == 1 && empty($property_id)) {
        $property_id = isset($_POST['property_id']) ? $_POST['property_id'] : 0;
    }

    // Tangani unggahan file
    if (isset($_FILES['contract']) && $_FILES['contract']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['contract']['tmp_name'];
        $fileName = $_FILES['contract']['name'];
        $fileSize = $_FILES['contract']['size'];
        $fileType = $_FILES['contract']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        $uploadFileDir = '../../uploads/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $years_worked = $newFileName; // Menggunakan kolom 'years_worked' untuk file
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengunggah file contract.']);
            exit;
        }
    }

    // Sisipkan data ke database
    $sql = "INSERT INTO employees (name, age, salary, years_worked, employment_status, position, property_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        if ($stmt->execute([$name, $age, $salary, $years_worked, $status, $position, $property_id])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan karyawan']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Permintaan tidak valid']);
}
