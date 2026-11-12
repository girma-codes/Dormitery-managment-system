<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: " . BASE_URL . "login.php");
        exit();
    }
}

function requireRole($role) {
    requireLogin();
    if ($_SESSION['user_type'] !== $role) {
        header("Location: " . BASE_URL . "unauthorized.php");
        exit();
    }
}

function requireRoles($roles) {
    requireLogin();
    if (!in_array($_SESSION['user_type'], $roles)) {
        header("Location: " . BASE_URL . "unauthorized.php");
        exit();
    }
}

function getCurrentUser() {
    return [
        'id'        => $_SESSION['user_id'] ?? null,
        'username'  => $_SESSION['username'] ?? null,
        'user_type' => $_SESSION['user_type'] ?? null,
        'fname'     => $_SESSION['fname'] ?? null,
        'lname'     => $_SESSION['lname'] ?? null,
    ];
}
?>
