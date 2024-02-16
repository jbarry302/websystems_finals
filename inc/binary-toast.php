<?php
/**
 * Displays a success or error message toast based on the GET parameter or provided variables.
 * @author jbarry302
 *
 * @param int $success   Set to 1 to display a success message toast
 * @param string $success_message The success message to display (optional)
 * @param string $error_message The error message to display (optional)
 *
 * Usage:
 *     Display a success message toast
 *     $success = 1;
 *     $success_message = "Registration successful! You can now log in.";
 *     include('inc/binary-toast.php');
 *
 *     Display an error message toast
 *     $error_message = "An error occurred while processing your request.";
 *     include('inc/binary-toast.php');
 */
?>

<?php if ((isset($_GET['success']) && $_GET['success'] == 1) || isset($success_message)) : ?>
    <div id="success-message" class="floating-div success">
        <?php if (isset($success_message)) {
            echo $success_message;
        } else {
            echo 'Registration successful! You can now log in.';
        } ?>
    </div>
<?php elseif (!empty($error_message)) : ?>
    <div id="error-message" class="floating-div fail">
        <?= $error_message ?>
    </div>
<?php endif ?>

<link rel="stylesheet" href="assets/css/binary-toast.css">