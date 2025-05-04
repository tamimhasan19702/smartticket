<?php
require_once __DIR__ . '/../includes/functions.php';

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Optional: Clear session cookie (for good measure)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Redirect to login/home page
header('Location: ' . url('index.php'));
exit;
?>