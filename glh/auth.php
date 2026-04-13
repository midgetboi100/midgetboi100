<?php
// Set session cookie to expire when browser closes
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => false,
        'httponly' => true
    ]);

    session_start();
}

// Check login
if (!isset($_SESSION["CustomerID"]) && !isset($_SESSION["ProducerID"])) {
    header("Location: login.php");
    exit();
}
?>