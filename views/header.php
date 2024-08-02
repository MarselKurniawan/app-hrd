<?php
include '../includes/db.php';
session_start(); // Make sure to start the session

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure the database connection is available
if (!$pdo) {
    die('Database connection failed.');
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die('Anda harus login terlebih dahulu.');
}

$user_id = $_SESSION['user_id'];

// Get user role
$stmt = $pdo->prepare("SELECT role_id FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die('User not found.');
}

$role = $user['role_id'];
$properties = [];

if ($role === 1) {
    // For admin, fetch all properties
    $stmt = $pdo->query("SELECT * FROM properties");
    $properties = $stmt->fetchAll();
} else if ($role === 2) {
    // For HRD, fetch properties managed by HRD
    $stmt = $pdo->prepare("SELECT * FROM properties WHERE id IN (SELECT property_id FROM hrd_properties WHERE hrd_id = ?)");
    $stmt->execute([$user_id]);
    $properties = $stmt->fetchAll();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var propertySelect = document.getElementById('property');

            function fetchEmployees(propertyId) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '/app-hrd/core/api/api_get_employees.php?property_id=' + propertyId, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.employees) {
                            updateEmployeeTable(response.employees);
                        }
                    }
                };
                xhr.send();
            }

            // Handle property change
            propertySelect.addEventListener('change', function() {
                var propertyId = this.value;
                fetchEmployees(propertyId);
            });

            // Auto-select property based on role (for HRD)
            if (propertySelect.options.length > 0) {
                var selectedPropertyId = propertySelect.value;
                fetchEmployees(selectedPropertyId);
            }
        });

        function updateEmployeeTable(employees) {
            var employeeTableBody = document.getElementById('employeeTableBody');
            employeeTableBody.innerHTML = '';

            employees.forEach(function(employee) {
                var row = document.createElement('tr');

                row.innerHTML = `
                <td>${employee.name}</td>
                <td>${employee.age}</td>
                <td>${employee.salary}</td>
                <td>${employee.years_worked}</td>
                <td>${employee.employment_status}</td>
                <td>${employee.position}</td>
                <td>${employee.property_id}</td>
            `;

                employeeTableBody.appendChild(row);
            });
        }
    </script>

</head>

<body>
    <div class="antialiased bg-gray-50 dark:bg-gray-900">
        <nav class="bg-white border-b border-gray-200 px-4 py-2.5 dark:bg-gray-800 dark:border-gray-700 fixed left-0 right-0 top-0 z-50">
            <div class="flex flex-wrap justify-between items-center">
                <div class="flex justify-start items-center">
                    <button data-drawer-target="drawer-navigation" data-drawer-toggle="drawer-navigation" aria-controls="drawer-navigation" class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer md:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <svg aria-hidden="true" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Toggle sidebar</span>
                    </button>
                    <a href="https://flowbite.com" class="flex items-center justify-between mr-4">
                        <img src="https://flowbite.s3.amazonaws.com/logo.svg" class="mr-3 h-8" alt="Flowbite Logo" />
                        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Flowbite</span>
                    </a>
                </div>
                <div class="flex items-center lg:order-2">
                    <select id="property" name="property" <?php if ($role === '2') echo 'disabled'; ?>>
                        <?php foreach ($properties as $property) : ?>
                            <option value="<?= htmlspecialchars($property['id']) ?>">
                                <?= htmlspecialchars($property['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="flex mx-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/michael-gough.png" alt="user photo" />
                    </button>
                    <!-- Dropdown menu -->
                    <div class="hidden z-50 my-4 w-56 text-base list-none bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600 rounded-xl" id="dropdown">
                        <div class="py-3 px-4">
                            <span class="block text-sm font-semibold text-gray-900 dark:text-white">Neil Sims</span>
                            <span class="block text-sm text-gray-900 truncate dark:text-white">name@flowbite.com</span>
                        </div>
                        <ul class="py-1 text-gray-700 dark:text-gray-300" aria-labelledby="dropdown">
                            <li>
                                <a href="#" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sign out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar -->

        <aside class="fixed top-0 left-0 z-40 w-64 h-screen pt-14  bg-white border-r border-gray-200 md:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidenav" id="drawer-navigation">
            <div class="overflow-y-auto py-5 px-3 h-full bg-white dark:bg-gray-800">
                <ul class="space-y-2">
                    <li>
                        <a href="/app-hrd/index.php" class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <svg aria-hidden="true" class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 3c-4.8 0-9 4.3-9 9 0 4.2 2.7 7.8 6.4 8.8.5.1.6-.2.6-.5 0-.3 0-1.1-.1-2.1-2.6.5-3.3-1.1-3.3-1.1-.4-1.1-1-1.4-1-1.4-.8-.6.1-.6.1-.6.9.1 1.3 1 1.3 1 .8 1.3 2.2.9 2.7.7.1-.6.3-1 .5-1.2-2.1-.2-4.3-1-4.3-4.4 0-1 .3-1.9 1-2.6-.1-.2-.4-1.2.1-2.5 0 0 .8-.3 2.8 1 .8-.2 1.6-.4 2.5-.4s1.7.1 2.5.4c2-1.4 2.8-1 2.8-1 .5 1.3.2 2.3.1 2.5.6.7 1 1.6 1 2.6 0 3.4-2.2 4.2-4.4 4.4.3.2.6.8.6 1.6 0 1.2-.1 2.1-.1 2.4 0 .3.2.7.7.5C18.3 19.8 21 16.2 21 12c0-4.7-4.2-9-9-9z"></path>
                            </svg>
                            <span class="ml-3">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="/app-hrd/core/employee.php" class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5 3a3 3 0 100 6 3 3 0 000-6zM5 7a1 1 0 110-2 1 1 0 010 2zm9-4a3 3 0 100 6 3 3 0 000-6zm0 4a1 1 0 110-2 1 1 0 010 2zM5 10a5 5 0 00-5 5 1 1 0 102 0 3 3 0 016 0 1 1 0 102 0 5 5 0 00-5-5zm7.5 2a4.5 4.5 0 00-3.28 1.443 4.455 4.455 0 00-.72.941c-.235.384-.464.816-.7 1.256-.12.227-.24.455-.36.683a1 1 0 001.8.9c.107-.199.21-.397.318-.597.222-.412.45-.843.7-1.256.252-.419.522-.835.822-1.207A2.5 2.5 0 0112.5 12z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-3">Employee Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="/app-hrd/core/roles.php" class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M9 3a1 1 0 00-1 1v2H6a1 1 0 100 2h2v2a1 1 0 102 0V8h2a1 1 0 100-2H9V4a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-3">Roles</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

