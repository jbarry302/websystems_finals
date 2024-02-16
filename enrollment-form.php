<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AcademiaHub: Enrollment Form</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="assets/css/enrollment-form.css">
    <script src="https://kit.fontawesome.com/a38ed59c98.js" crossorigin="anonymous"></script>
</head>

<body>

    <!-- <header> -->
    <?php include('inc/header.php'); ?>
    <!-- </header> -->

    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        $accountId = $user['id'];
    }

    

    $emailError = '';
    $yearsError = '';
    $semestersError = '';
    $firstNameError = '';
    $lastNameError = '';
    $birthdayError = '';
    $regionError = '';
    $provinceError = '';
    $cityError = '';
    $barangayError = '';
    $code = $sy = $programId = '';
    $enrollmentStatusId = '1';
    $startDate = date('Y-m-d');

    if (isset($_GET['code'])) {
        $code = $_GET['code'];

        $qs = "SELECT `id`, `years`, `semesters` FROM `programs` WHERE `code` = '$code'";
        $q = mysqli_query($conn, $qs);
        $sy = mysqli_num_rows($q) > 0 ? mysqli_fetch_assoc($q) : null;

        if (!$sy) {
            header('Location: program-list.php');
            exit;
        }

        $programId = $sy['id'];

        // echo json_encode($sy);
    } else {
        header('Location: program-list.php');
        exit;
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST)) {
            $email = $_POST['email'];
            $years = $_POST['years'];
            $semesters = $_POST['semesters'];
            $firstName = $_POST['first_name'];
            $middleName = $_POST['middle_name'];
            $lastName = $_POST['last_name'];
            $birthday = $_POST['birthday'];
            $region = $_POST['region'];
            $province = $_POST['province'];
            $city = $_POST['city'];
            $barangay = $_POST['barangay'];
            $landmark = $_POST['landmark'];

            if (empty($email)) {
                $emailError = 'Email is required';
            } else {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailError = 'Invalid email format';
                }
            }

            if (empty($years)) {
                $yearsError = 'Year is required';
            }

            if (empty($semesters)) {
                $semestersError = 'Semester is required';
            }

            if (empty($firstName)) {
                $firstNameError = 'First name is required';
            }

            if (empty($lastName)) {
                $lastNameError = 'Last name is required';
            }

            if (empty($birthday)) {
                $birthdayError = 'Birthday is required';
            }

            if (empty($region)) {
                $regionError = 'Region is required';
            }

            if (empty($province)) {
                $provinceError = 'Province is required';
            }

            if (empty($city)) {
                $cityError = 'City is required';
            }

            if (empty($barangay)) {
                $barangayError = 'Barangay is required';
            }

            if (empty($emailError) && empty($yearsError) && empty($semestersError) && empty($firstNameError) && empty($lastNameError) && empty($birthdayError) && empty($regionError) && empty($provinceError) && empty($cityError) && empty($barangayError)) {
                // true
                $stmt = $conn->prepare("INSERT INTO enrollment_history (program_id, account_id, enrollment_status_id, curriculum_year, curriculum_semester, start_date) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iiiiis", $programId, $accountId, $enrollmentStatusId, $years, $semesters, $startDate);

                if ($stmt->execute()) {
                    header('Location: enrollment-status.php');
                    exit;
                } else {
                    $error_message = mysqli_error($conn);
                }

                $stmt->close();
                $conn->close();
            } else {
                $error_message = 'Please fill out all the required fields';
            }
        }
    }
    ?>

    <?php include('inc/binary-toast.php'); ?>


    <?php
    $condition = isset($_SESSION['email']) ? false : true;
    $headerMsg = "Enrollment Form";
    // $paragraphMsg = "you can create an account to enroll or proceed via email";
    $paragraphMsg = "you must login into an account to enroll";
    $postiveBtn = '<a href="login.php" class="btn">Login</a>';
    // $negativeBtn = '<button type="submit" class="btn" onclick="removeModalBackdrop()">Proceed anyway</button>';
    $negativeBtn = '<div></div>';
    include('inc/binary-prompt.php');

    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $qs = "SELECT * FROM accounts WHERE email = '$email'";
        $q = mysqli_query($conn, $qs);
        $user = mysqli_num_rows($q) > 0 ? mysqli_fetch_assoc($q) : null;
    }


    ?>



    <main>
        <h1 style="text-align: center;">Enrollment form: <?= $code ?></h1>
        <!-- Enrollment form -->
        <form action="enrollment-form.php?code=<?= $code ?>" method="POST">
            <div class="form-group">
                <label for="years">Year (S.Y. <?= date('Y') ?>-<?= date('Y') + 1 ?>)</label>
                <select name="years" id="years">
                    <?php for ($i = 1; $i <= intval($sy['years']); $i++) { ?>
                        <?php if ($i === 1) : ?>
                            <option value="<?= $i ?>" selected><?= $i ?></option>
                        <?php else : ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endif; ?>
                    <?php } ?>
                </select>
                <span class="error-message"><?php echo $yearsError; ?></span>
            </div>

            <div class="form-group">
                <label for="semesters">Semester</label>
                <select name="semesters" id="semesters">
                    <?php for ($i = 1; $i <= intval($sy['semesters']); $i++) { ?>
                        <?php if ($i === 1) : ?>
                            <option value="<?= $i ?>" selected><?= $i ?></option>
                        <?php else : ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endif; ?>
                    <?php } ?>
                </select>
                <span class="error-message"><?php echo $semestersError; ?></span>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= isset($user['email']) ? $user['email'] : '' ?>" required>
                <span class="error-message"><?php echo $emailError; ?></span>
            </div>

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?= isset($user['first_name']) ? $user['first_name'] : '' ?>" required>
                <span class="error-message"><?php echo $firstNameError; ?></span>
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name (Optional)</label>
                <input type="text" id="middle_name" name="middle_name" value="<?= isset($user['middle_name']) ? $user['middle_name'] : '' ?>">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?= isset($user['last_name']) ? $user['last_name'] : '' ?>" required>
                <span class="error-message"><?php echo $lastNameError; ?></span>
            </div>

            <div class="form-group">
                <label for="birthday">Birthday</label>
                <input type="date" id="birthday" name="birthday" value="<?= isset($user['birthday']) ? $user['birthday'] : '' ?>" required>
                <span class="error-message"><?php echo $birthdayError; ?></span>
            </div>

            <div class="form-group">
                <label for="region">Region</label>
                <select name="region" id="region-dropdown" required>
                    <?php if (isset($user['region']) && false) : ?>
                        <option value="<?= $user['region'] ?>" selected><?= $user['region'] ?></option>
                    <?php endif; ?>
                </select>
                <span class="error-message"><?php echo $regionError; ?></span>
            </div>

            <div class="form-group">
                <label for="province">Province</label>
                <input type="text" id="province" name="province" value="<?= isset($user['province']) ? $user['province'] : '' ?>" required>
                <span class="error-message"><?php echo $provinceError; ?></span>
            </div>

            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" value="<?= isset($user['city']) ? $user['city'] : '' ?>" required>
                <span class="error-message"><?php echo $cityError; ?></span>
            </div>

            <div class="form-group">
                <label for="barangay">Barangay</label>
                <input type="text" id="barangay" name="barangay" value="<?= isset($user['barangay']) ? $user['barangay'] : '' ?>" required>
                <span class="error-message"><?php echo $barangayError; ?></span>
            </div>

            <div class="form-group">
                <label for="landmark">Landmark</label>
                <input type="text" id="landmark" name="landmark" value="<?= isset($user['landmark']) ? $user['landmark'] : '' ?>">
            </div>

            <div class="form-group">
                <button type="submit">Submit</button>
            </div>
        </form>
    </main>



    <!-- <footer> -->
    <?php include('inc/footer.php'); ?>
    <!-- </footer> -->
    <script>
        function removeModalBackdrop() {
            const modalBackdrop = document.querySelector('.modal-backdrop');
            if (modalBackdrop) {
                modalBackdrop.textContent = '';
                modalBackdrop.remove();
            }
        }


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
    <script defer src="https://f001.backblazeb2.com/file/buonzz-assets/jquery.ph-locations-v1.0.0.js"></script>
    <script src="assets/js/register.js"></script>

</body>

</html>