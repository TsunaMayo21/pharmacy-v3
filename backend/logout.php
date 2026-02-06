<?php
session_start(); // Start the session to access it

// 1. Unset all session variables
$_SESSION = array();

// 2. Destroy the session cookie if it exists
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// 3. Destroy the session
session_destroy();

// 4. Redirect to the login page in the frontend folder
header("Location: ../frontend/login.html");
exit();
?>