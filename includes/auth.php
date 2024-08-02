<?php
session_start();
require_once __DIR__ . '/db.php';

function login($email, $password)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // Debug: Check fetched user
        error_log(print_r($user, true));

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];
            return true;
        } else {
            // Debug: Password mismatch
            error_log('Password mismatch');
        }
    } else {
        // Debug: User not found
        error_log('User not found');
    }
    return false;
}

function auth_check()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: /app-hrd/login');
        exit;
    }
}

function get_user_property_id($user_id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT property_id FROM property_user WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchColumn();
}

function logout()
{
    session_destroy();
    header('Location: /app-hrd/login');
    exit;
}
