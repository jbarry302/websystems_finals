<?php
// Create Connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
date_default_timezone_set('Asia/Manila');

// Check Connection
if (mysqli_connect_errno()) {
    // Connection Failed
    echo 'Failed to connect to MySQL ' . mysqli_connect_errno();
}
