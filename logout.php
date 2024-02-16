<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if a user is logged in
if (isset($_SESSION['email'])) {
    // Clear session data
    session_unset();

    // Destroy the session
    session_destroy();
} else {
    header('Location: login.php');
}

header('Location: index.php');
