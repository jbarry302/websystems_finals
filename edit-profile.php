<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AcademiaHub: Edit Profile</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="assets/css/edit-profile.css">
</head>

<body>
    <?php
    include('inc/header.php');

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user'])) {
        header('Location: index.php');
        exit;
    }

    require_once('inc/get_location.php');

    $user = $_SESSION['user'];

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

    // Define variables to hold user input
    $email = $user['email'];
    $first_name = $user['first_name'];
    $middle_name = $user['middle_name'];
    $last_name = $user['last_name'];
    $birthday = $user['birthday'];
    $region = $user['region'];
    $province = $user['province'];
    $city = $user['city'];
    $barangay = $user['barangay'];
    $landmark = $user['landmark'];


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the submitted email and password
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

        if (empty($email)) {
            $emailError = 'Email is required.';
        } else {
            $email = mysqli_real_escape_string($conn, $email);
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            if (!$email) {
                $emailError = 'Please enter a valid email.';
            }
        }

        if (empty($first_name)) {
            $firstNameError = 'First name is required.';
        }

        if (empty($last_name)) {
            $lastNameError = 'Last name is required.';
        }

        if (empty($birthday)) {
            $birthdayError = 'Birthday is required.';
        }

        if (empty($region)) {
            $regionError = 'Region is required.';
        }

        if (empty($province)) {
            $provinceError = 'Province is required.';
        }

        if (empty($city)) {
            $cityError = 'City is required.';
        }

        if (empty($barangay)) {
            $barangayError = 'Barangay is required.';
        }

        if (empty($emailError) && empty($firstNameError) && empty($middleNameError) && empty($lastNameError) && empty($birthdayError) && empty($regionError) && empty($provinceError) && empty($cityError) && empty($barangayError)) {

            $qs = "UPDATE accounts SET email = '$email', first_name = '$first_name', middle_name = '$middle_name', last_name = '$last_name', birthday = '$birthday', region = '$region', province = '$province', city = '$city', barangay = '$barangay', landmark = '$landmark' WHERE id = " . $user['id'];
            $q = mysqli_query($conn, $qs);

            if ($q) {
                $user['email'] = $email;
                $user['first_name'] = $first_name;
                $user['middle_name'] = $middle_name;
                $user['last_name'] = $last_name;
                $user['birthday'] = $birthday;
                $user['region_id'] = $region;
                $user['province_id'] = $province;
                $user['city_id'] = $city;
                $user['barangay_id'] = $barangay;
                $user['landmark'] = $landmark;
                $_SESSION['user'] = $user;

                $success_message = 'Profile updated successfully.';
            } else {
                // $error_message = 'Something went wrong. Please try again.';
                $error_message = mysqli_error($conn);
            }
        } else {
            $error_message = 'Please enter all required fields.';
        }
    }

    ?>


    <?php include('inc/binary-toast.php') ?>

    <main>

        <div class="edit-profile-container">
            <form method="POST" action="edit-profile.php">


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
                    <select name="province" id="province-dropdown" required>
                        <?php if (isset($user['province'])) : ?>
                            <option value="<?= $user['province'] ?>" selected><?= $user['province'] ?></option>
                        <?php endif; ?>
                    </select>
                    <span class="error-message"><?php echo $provinceError; ?></span>
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <select name="city" id="city-dropdown" required>
                        <?php if (isset($user['city'])) : ?>
                            <option value="<?= $user['city'] ?>" selected><?= $user['city'] ?></option>
                        <?php endif; ?>
                    </select>
                    <span class="error-message"><?php echo $cityError; ?></span>
                </div>

                <div class="form-group">
                    <label for="barangay">Barangay</label>
                    <select name="barangay" id="barangay-dropdown" required>
                        <?php if (isset($user['barangay'])) : ?>
                            <option value="<?= $user['barangay'] ?>" selected><?= $user['barangay'] ?></option>
                        <?php endif; ?>
                    </select>
                    <span class="error-message"><?php echo $barangayError; ?></span>
                </div>

                <div class="form-group">
                    <label for="last_name">Landmark (Optional)</label>
                    <input type="text" id="landmark" name="landmark" value="<?= isset($landmark) ? $landmark : '' ?>" required>
                </div>

                <button type="submit" class="btn">Update</button>
            </form>
        </div>


    </main>


    <?php include('inc/footer.php'); ?>
    <script>
        const regionSelected = "<?= $user['region'] ?? 'AUTONOMOUS REGION IN MUSLIM MINDANAO (ARMM)' ?>";
        // console.log(`region selected: ${regionSelected}`);
        fetch('assets/json/regions.json')
            .then(response => response.json())
            .then(data => {
                const regionDropdown = document.getElementById('region-dropdown');
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

</body>

</html>