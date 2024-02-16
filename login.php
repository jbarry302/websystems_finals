<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/login.css">
    <title>AcademiaHub: Login</title>

    <script src="https://kit.fontawesome.com/a38ed59c98.js" crossorigin="anonymous"></script>
</head>

<body>

    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['email'])) {
        header('Location: index.php');
        exit;
    }
    ?>


    <?php
    require_once('config/db.php');
    require_once('config/config.php');

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the submitted email and password
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate the email and password
        if (empty($email) || empty($password)) {
            $error_message = "Please enter both email and password.";
        } else {
            // Sanitize the email to prevent SQL injection
            $email = mysqli_real_escape_string($conn, $email);

            // Query the database to check if the user exists
            $query = "SELECT * FROM accounts WHERE email = '$email'";
            $result = mysqli_query($conn, $query);

            // echo mysqli_num_rows($result);
            // echo json_encode($result);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $stored_password = $row['password'];

                // Verify the password
                if (password_verify($password, $stored_password)) {
                    // Password is correct, set session variables and redirect to the dashboard
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['user'] = $row;

                    // Check if "Remember me" is checked
                    if (isset($_POST['remember-me'])) {
                        // Set a cookie with the user's credentials
                        $cookie_name = 'remember_user';
                        $cookie_value = base64_encode($row['email'] . '|' . $password);
                        setcookie($cookie_name, $cookie_value, time() + (30 * 24 * 60 * 60), '/'); // Cookie expires after 30 days
                    }

                    // Redirect to the dashboard or any other page
                    header('Location: index.php');
                    exit;
                } else {
                    $error_message = "Invalid email or password.";
                }
            } else {
                $error_message = "Invalid password or email.";
            }
        }
    } else {
        // Check if the "Remember me" cookie exists
        if (isset($_COOKIE['remember_user'])) {
            // Extract the stored credentials from the cookie
            $cookie_value = $_COOKIE['remember_user'];
            $credentials = explode('|', base64_decode($cookie_value));
            $email = $credentials[0];
            $password = $credentials[1];

            // Clear the invalid cookie
            // setcookie('remember_user', '', time() - 3600, '/');
        }
    }
    ?>

    <?php include('inc/binary-toast.php'); ?>


    <div class="login-container">
        <div class="login-card">
            <h2>Login</h2>
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" value="<?= $email ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-input">
                        <input type="password" class="password-input-field" id="password" name="password" value="<?= $password ?? '' ?>" required>
                        <span class="toggle-password" onclick="togglePasswordVisibility(this.parentNode)">
                            <i class="fas fa-eye-slash"></i>
                        </span>
                    </div>
                </div>

                <div class="additional-options">
                    <label for="remember-me">
                        <input type="checkbox" id="remember-me" name="remember-me">
                        Remember me
                    </label>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
                <div class="register-link">
                    <p>Don't have an account? <a href="register.php">Register</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/login.js"></script>
</body>

</html>