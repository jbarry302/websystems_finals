<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/register.css">
    <title>AcademiaHub: Register</title>

    <script src="https://kit.fontawesome.com/a38ed59c98.js" crossorigin="anonymous"></script>

</head>

<body>
    <?php
    require_once('config/db.php');
    require_once('inc/get_location.php');


    // Define variables to hold error messages
    $regionName = $provinceName = $cityName = $barangayName = '';
    $usernameError = '';
    $emailError = '';
    $firstNameError = '';
    $middleNameError = '';
    $lastNameError = '';
    $birthdayError = '';
    $regionError = '';
    $provinceError = '';
    $cityError = '';
    $barangayError = '';
    $passwordError = '';
    $confirmPasswordError = '';


    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // echo json_encode($_POST);
        // Get the form data
        // $username = $_POST['username'];
        $email = $_POST['email'];
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name = $_POST['last_name'];
        $birthday = $_POST['birthday'];
        $region = $_POST['region'];
        $province = $_POST['province'];
        $city = $_POST['city'];
        $barangay = $_POST['barangay'];
        $landmark = $_POST['landmark'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $role = "student";

        // Perform input validation
        // if (empty($username)) {
        //     $usernameError = 'Please enter a username.';
        // }

        if (empty($email)) {
            $emailError = 'Please enter an email.';
        }

        if (empty($first_name)) {
            $firstNameError = 'Please enter your first name.';
        }

        if (empty($last_name)) {
            $lastNameError = 'Please enter your last name.';
        }

        if (empty($birthday)) {
            $birthdayError = 'Please enter your birthday.';
        }

        if (empty($region)) {
            $regionError = 'Please enter your region.';
        } else {
            $regionName = getLocationName('regions.json', $region);
        }

        if (empty($province)) {
            $provinceError = 'Please enter your province.';
        } else {
            $provinceName = getLocationName('provinces.json', $province);
        }

        if (empty($city)) {
            $cityError = 'Please enter your city.';
        } else {
            $cityName = getLocationName('cities.json', $city);
        }

        if (empty($barangay)) {
            $barangayError = 'Please enter your barangay.';
        } else {
            $barangayName = getLocationName('barangays.json', $barangay);
        }

        if (empty($password)) {
            $passwordError = 'Please enter a password.';
        }

        if (empty($confirmPassword)) {
            $confirmPasswordError = 'Please confirm your password.';
        } elseif ($password !== $confirmPassword) {
            $confirmPasswordError = 'Passwords do not match.';
        }

        // If there are no validation errors, proceed with registration
        if (empty($emailError) && empty($firstNameError) && empty($lastNameError) && empty($birthdayError) && empty($provinceError) && empty($cityError) && empty($barangayError) && empty($passwordError) && empty($confirmPasswordError)) {
            // Generate a secure password hash
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Create a new mysqli connection
            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // Check for connection errors
            if (mysqli_connect_errno()) {
                // Handle connection error
                echo "Failed to connect to MySQL: " . mysqli_connect_errno();
                exit;
            }

            // Prepare the SQL statement
            $stmt = $conn->prepare("INSERT INTO accounts (email, password, role, first_name, middle_name, last_name, birthday, region, province, city, barangay, landmark) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            // Bind the parameters to the statement
            $stmt->bind_param("ssssssssssss", $email, $hashedPassword, $role, $first_name, $middle_name, $last_name, $birthday, $regionName, $provinceName, $cityName, $barangayName, $landmark);


            // Execute the statement
            if ($stmt->execute()) {
                // Redirect the user to the login page
                header('Location: login.php?success=1');
                exit;
            } else {
                // Handle insertion error
                $errorInfo = mysqli_error($conn);
                $splitted_parts = explode(" ", $errorInfo);
                $column = end($splitted_parts);


                if (str_starts_with($errorInfo, "Duplicate entry")) {
                    $error_message = sprintf("%s already exists.", $column);
                } else {
                    $error_message = "Failed to register. Please try again.";
                }
            }

            // Close the statement and connection
            $stmt->close();
            $conn->close();
        } else {
            $error_message = "The form submitted contains invalid values, please check your inputs.";
        }
    }
    ?>


    <?php include('inc/binary-toast.php') ?>

    <div class="login-container">
        <div class="login-card">
            <h2>Register</h2>
            <form method="POST" action="register.php">


                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" autocomplete="username" value="<?= isset($email) ? $email : '' ?>" required>
                    <span class="error-message"><?php echo $emailError; ?></span>
                </div>
                <!-- rest of the input fields -->

                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="<?= isset($first_name) ? $first_name : '' ?>" required oninput="capitalizeInput(this)">
                    <span class="error-message"><?php echo $firstNameError; ?></span>
                </div>

                <div class="form-group">
                    <label for="middle_name">Full Middle Name (Optional)</label>
                    <input type="text" id="middle_name" name="middle_name" value="<?= isset($middle_name) ? $middle_name : '' ?>" oninput="capitalizeInput(this)">
                    <span class="error-message"><?php echo $middleNameError; ?></span>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="<?= isset($last_name) ? $last_name : '' ?>" required oninput="capitalizeInput(this)">
                    <span class="error-message"><?php echo $lastNameError; ?></span>
                </div>

                <div class="form-group">
                    <label for="birthday">Birthday</label>
                    <input type="date" id="birthday" name="birthday" value="<?= isset($birthday) ? $birthday : '' ?>" required>
                    <span class="error-message"><?php echo $birthdayError; ?></span>
                </div>

                <div class="form-group">
                    <label for="region">Region</label>
                    <select name="region" id="region-dropdown" required></select>
                    <span class="error-message"><?php echo $regionError; ?></span>
                </div>

                <div class="form-group">
                    <label for="province">Province</label>
                    <select name="province" id="province-dropdown" required></select>
                    <span class="error-message"><?php echo $provinceError; ?></span>
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <select name="city" id="city-dropdown" required></select>
                    <span class="error-message"><?php echo $cityError; ?></span>
                </div>

                <div class="form-group">
                    <label for="barangay">Barangay</label>
                    <select name="barangay" id="barangay-dropdown" required></select>
                    <span class="error-message"><?php echo $barangayError; ?></span>
                </div>

                <div class="form-group">
                    <label for="last_name">Landmark (Optional)</label>
                    <input type="text" id="landmark" name="landmark" value="<?= isset($landmark) ? $landmark : '' ?>" required>
                </div>

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

                <button type="submit" class="btn">Register</button>
                <div class="register-link">
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        const regionSelected = "<?= $regionName ?? 'AUTONOMOUS REGION IN MUSLIM MINDANAO (ARMM)' ?>";
        const regionDropdown = document.getElementById('region-dropdown');
        // console.log(`region selected: ${regionSelected}`);
        fetch('assets/json/regions.json')
            .then(response => response.json())
            .then(data => {
                data.data.forEach(region => {
                    const option = document.createElement('option');
                    option.value = region.id;
                    option.textContent = region.name;
                    if (region.name === regionSelected) option.selected = true;
                    regionDropdown.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching provinces data:', error);
            });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://f001.backblazeb2.com/file/buonzz-assets/jquery.ph-locations-v1.0.0.js"></script>
    <script src="assets/js/register.js"></script>
    <script src="assets/js/login.js"></script>
</body>

</html>