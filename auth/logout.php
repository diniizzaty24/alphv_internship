<?php
// logout.php
// Handles user logout and session destruction

// Start session
session_start();

// Clear all session data
$_SESSION = []; // or session_unset() for older PHP versions

// Destroy the session and session cookie
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

// Destroy the session
session_destroy();

// Set anti-caching headers to prevent back button issues
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

// Redirect to login page with absolute URL
header("Location: ../auth/login.php");
exit();
?>