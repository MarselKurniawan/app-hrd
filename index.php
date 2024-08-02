<?php
$request = $_SERVER['REQUEST_URI'];
echo "Request URI: " . htmlspecialchars($request) . "<br>";

if (strpos($request, '?') !== false) {
    $request = substr($request, 0, strpos($request, '?'));
}
echo "Stripped Request URI: " . htmlspecialchars($request) . "<br>";

$routes = [
    '/app-hrd/' => '/../views/login.php',
    '/app-hrd/login' => '/../views/login.php',
    '/app-hrd/employee' => '/../views/employee.php',
    '/app-hrd/hrd' => '/../views/hrd.php',
    '/app-hrd/home' => '/../views/home.php',
];

if (array_key_exists($request, $routes)) {
    require_once __DIR__ . '/../includes/functions.php';
    require_once __DIR__ . '/../includes/auth.php';
    require_once __DIR__ . $routes[$request];
} else {
    http_response_code(404);
    require_once __DIR__ . '/../views/404.php';
}

