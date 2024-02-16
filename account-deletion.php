<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AcademiaHub: Account</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assets/css/register.css">

    <style>
        main {
            margin-top: 80px;
            margin-bottom: 30px;
        }
    </style>

    <script src="https://kit.fontawesome.com/a38ed59c98.js" crossorigin="anonymous"></script>
</head>

<body>

    <?php
    include('inc/header.php');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $user = $accountId = null;

    $passwordError = $confirmPasswordError = '';

    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        $accountId = $user['id'];
    } else {
        header('Location: index.php');
        exit;
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete-account']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            if (empty($password)) {
                $passwordError = 'Password is required';
            }

            $qs = "SELECT password FROM accounts WHERE id = $accountId";
            $q = mysqli_query($conn, $qs);
            $row = mysqli_fetch_assoc($q);
            $hashedPassword = $row['password'];

            if (!password_verify($password, $hashedPassword)) {
                $error_message = 'Password does not match.';
            } else {
                $sql = "DELETE FROM accounts WHERE id = $accountId";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    session_destroy();

                    header('Location: login.php');
                    exit;
                } else {
                    $error_message = 'Something went wrong. Please try again.';
                }
            }
        }
    }

    ?>

    <?php
    $condition = (isset($_SESSION['user']) && !isset($_POST['delete-account'])) ? true : false;
    $headerMsg = "Do you want to proceed?";
    // $paragraphMsg = "you can create an account to enroll or proceed via email";
    $paragraphMsg = "Proceeding will delete your account and all your data.";
    $postiveBtn = '<div href="" class="btn" onclick="removeModalBackdrop()">Proceed anyway</div>';
    $negativeBtn = '<a href="index.php" class="btn">Go back</a>';

    ?>
    <?php
    include('inc/binary-prompt.php');
    include('inc/binary-toast.php');
    ?>

    <main>
        <div class="login-container">
            <div class="login-card">
                <h2>Delete Account</h2>
                <form method="POST" action="account-deletion.php">

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-input">
                            <input type="password" class="password-input-field" id="password" name="password" autocomplete="new-password" value="<?= isset($password) ? $password : '' ?>" required>
                            <span class="toggle-password" onclick="togglePasswordVisibility(this.parentNode)">
                                <i class="fas fa-eye-slash"></i>
                            </span>
                        </div>
                        <span class="error-message"><?php echo $passwordError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="password">Confirm Password</label>
                        <div class="confirm-password-input">
                            <input type="password" class="password-input-field" id="confirm_password" name="confirm_password" autocomplete="new-password" value="<?= isset($confirmPassword) ? $confirmPassword : '' ?>" required>
                            <span class="toggle-password" onclick="togglePasswordVisibility(this.parentNode)">
                                <i class="fas fa-eye-slash"></i>
                            </span>
                        </div>
                        <span class="error-message"><?php echo $confirmPasswordError; ?></span>
                    </div>

                    <button type="submit" class="btn" name="delete-account">Delete Account</button>
                </form>
            </div>
        </div>
    </main>


    <?php include('inc/footer.php'); ?>

    <script>
        function removeModalBackdrop() {
            const modalBackdrop = document.querySelector('.modal-backdrop');
            if (modalBackdrop) {
                modalBackdrop.textContent = '';
                modalBackdrop.remove();
            }
        }
    </script>
    <script src="assets/js/login.js"></script>

</body>

</html>