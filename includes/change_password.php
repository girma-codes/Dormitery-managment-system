<?php
// Generic change password handler - include after requiring config
$cpErrors = [];
$cpSuccess = '';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['change_password'])) {
    $current = $_POST['current_password']??'';
    $newp    = $_POST['new_password']??'';
    $confirm = $_POST['confirm_password']??'';
    $uid     = $_SESSION['user_id'];

    $user = $conn->query("SELECT password FROM users WHERE id=$uid")->fetch_assoc();
    if (!password_verify($current, $user['password'])) {
        $cpErrors[] = 'Current password is incorrect.';
    } elseif (strlen($newp) < 6) {
        $cpErrors[] = 'New password must be at least 6 characters.';
    } elseif ($newp !== $confirm) {
        $cpErrors[] = 'Passwords do not match.';
    } else {
        $hashed = password_hash($newp, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password='$hashed' WHERE id=$uid");
        $cpSuccess = 'Password changed successfully!';
    }
}
?>
