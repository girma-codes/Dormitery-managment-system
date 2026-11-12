<?php
require_once 'includes/config.php';
if (isLoggedIn()) {
    $uid = $_SESSION['user_id'];
    $ip  = $_SERVER['REMOTE_ADDR'];
    $conn->query("INSERT INTO activity_log (user_id, action, ip_address) VALUES ($uid, 'User logged out', '$ip')");
}
session_destroy();
header("Location: " . BASE_URL . "login.php");
exit();
?>
