<?php
define('BASE_URL', '/uog-dms/');
define('SITE_NAME', 'UoG Dormitory Management System');
define('UNIVERSITY_NAME', 'University of Gondar');
define('CAMPUS_NAME', 'Atse Tewodros Campus');

// Include database and session
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/session.php';

// Helper: sanitize input
function sanitize($data) {
    global $conn;
    return htmlspecialchars(strip_tags(trim($conn->real_escape_string($data))));
}

// Helper: redirect
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

// Helper: show flash message
function setFlash($type, $message) {
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_message'] = $message;
}

function getFlash() {
    if (isset($_SESSION['flash_message'])) {
        $html = '<div class="alert alert-' . $_SESSION['flash_type'] . ' alert-dismissible">'
              . '<button type="button" class="close" data-dismiss="alert">&times;</button>'
              . htmlspecialchars($_SESSION['flash_message'])
              . '</div>';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        return $html;
    }
    return '';
}
?>
