<!-- strict-modal.php -->

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Access the user account values from the session variables
if (!isset($_SESSION['email']) && false) {
    // User is not logged in, display the modal
?>
    <div class="modal-backdrop">
        <div class="modal-content">
            <h2>Login Required</h2>
            <p>You need to log in to access this page.</p>
            <div class="modal-buttons">
                <a href="index.php" class="btn">Go Back</a>
                <a href="login.php" class="btn">Login</a>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="assets/css/strict-modal.css">
<?php
}
?>