<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AcademiaHub: Enrollment Status</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="assets/css/enrollment-status.css">
</head>

<body>
    <?php include('inc/header.php'); ?>

    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['email'])) {
        $user = $_SESSION['user'];
        $email = $_SESSION['email'];
        $role = $_SESSION['role'];
        $id = $user['id'];
    } else {
        header('Location: login.php');
        exit;
    }

    $qs = "SELECT eh.curriculum_year, eh.curriculum_semester, eh.start_date, eh.end_date, p.title AS program_title, es.name AS enrollment_status_name
    FROM enrollment_history eh
    INNER JOIN programs p ON eh.program_id = p.id
    INNER JOIN enrollment_status es ON eh.enrollment_status_id = es.id
    WHERE eh.account_id = $id
    ORDER BY eh.start_date DESC";
    // ORDER BY es.name";

    $q = mysqli_query($conn, $qs);
    $enrollmentStatuses = mysqli_num_rows($q) > 0 ? mysqli_fetch_all($q, MYSQLI_ASSOC) : [];

    function getStatusIconClass($statusName)
    {
        switch ($statusName) {
            case 'APPLIED':
                return 'fas fa-file-alt';
            case 'PROCESSING':
                return 'fas fa-spinner';
            case 'ACCEPTED':
                return 'fas fa-check-circle';
            case 'REJECTED':
                return 'fas fa-times-circle';
            case 'ON-HOLD':
                return 'fas fa-pause-circle';
            default:
                return '';
        }
    }

    ?>


    <main>
        <?php if (count($enrollmentStatuses) === 0) : ?>
            <div class="empty-enrollment" style="height: 100dvh; width: 100%; display: flex; flex-direction: column; align-items:center; justify-content:center;">
                <h2>You have no enrollments yet.</h2>
            </div>
        <?php else : ?>
            <h1>Enrollment Status</h1>
            <div class="enrollment-status-list">
                <?php foreach ($enrollmentStatuses as $status) : ?>
                    <div class="enrollment-status <?= $status['enrollment_status_name'] ?>">
                        <div class="enrollment-header">
                            <i class="<?= getStatusIconClass($status['enrollment_status_name']) ?>"></i>
                            <h2><?= $status['program_title'] ?></h2>
                        </div>
                        <div class="enrollment-details">
                            <p>Status: <?= $status['enrollment_status_name'] ?></p>
                            <p>Curriculum Year: <?= $status['curriculum_year'] ?></p>
                            <p>Curriculum Semester: <?= $status['curriculum_semester'] ?></p>
                            <p>Start Date: <?= date('M j, Y', strtotime($status['start_date'])) ?></p>
                            <p>End Date: <?= $status['end_date'] ? date('M j, Y', strtotime($status['end_date'])) : '' ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>




    <?php include('inc/footer.php'); ?>
</body>

</html>