<?php
require_once('config/db.php');
require_once('config/config.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

if($_SESSION['role'] !== 'admin') {
    echo 'You are not authorized to access this page.';
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enrollmentId = $_POST['enrollmentId'];
    $statusId = $_POST['status'];

    $qs = "UPDATE enrollment_history SET enrollment_status_id = $statusId WHERE id = $enrollmentId";
    $q = mysqli_query($conn, $qs);

    if($q) {
        header('Location: enrollment-list.php');
    } else {
        echo 'An error occurred while updating enrollment status.';
    }
}

?>