<?php
session_start();
session_unset(); // remove all session variables
session_destroy(); // destroy the session

// Optional: clear session cookie
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to home or login page
header("Location: ../index.php");
exit;
?>
