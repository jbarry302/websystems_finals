<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment List</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="assets/css/enrollment-list.css">
</head>
<body>
    <?php include('inc/header.php'); ?>
    <?php
    if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
        header('Location: login.php');
        exit;
    } 

    $qs = "
        SELECT
            eh.id AS 'txn_id',
            a.id AS 'student_id',
            CONCAT(a.first_name, ' ', a.last_name) AS 'student',
            p.title AS 'program',
            CASE
                WHEN eh.curriculum_year = 1 THEN '1st Year'
                WHEN eh.curriculum_year = 2 THEN '2nd Year'
                WHEN eh.curriculum_year = 3 THEN '3rd Year'
                WHEN eh.curriculum_year = 4 THEN '4th Year'
                ELSE 'Unkown'
            END AS 'curriculum_year',
            CASE
                WHEN eh.curriculum_semester = 1 THEN '1st Semester'
                WHEN eh.curriculum_semester = 1 THEN '2nd Semester'
                ELSE 'Unkown'
            END AS 'curriculum_semester',
                eh.start_date,
                eh.end_date,
                es.name AS 'status'
        FROM `enrollment_history` eh 
        INNER JOIN programs p ON p.id = eh.program_id
        INNER JOIN accounts a ON a.id = eh.account_id
        INNER JOIN enrollment_status es ON es.id = eh.enrollment_status_id
        ORDER BY eh.id DESC;
    ";
    $q = mysqli_query($conn, $qs);

    $enrollments = mysqli_num_rows($q) > 0 ? mysqli_fetch_all($q, MYSQLI_ASSOC) : [];

    $qs = "SELECT id, name FROM enrollment_status";
    $q = mysqli_query($conn, $qs);
    $enrollmentStatuses = mysqli_num_rows($q) > 0 ? mysqli_fetch_all($q, MYSQLI_ASSOC) : [];
    ?>

    <main>
        <div class="container">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Program</th>
                        <th>Curriculum Year</th>
                        <th>Curriculum Semester</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $enrollment) : ?>
                        <tr>
                            <td><?= $enrollment['student'] ?></td>
                            <td><?= $enrollment['program'] ?></td>
                            <td><?= $enrollment['curriculum_year'] ?></td>
                            <td><?= $enrollment['curriculum_semester'] ?></td>
                            <td><?= $enrollment['start_date'] ?></td>
                            <td><?= $enrollment['end_date'] ?></td>
                            <td>
                                <form action="update-enrollment-status.php" method="post">
                                    <input type="hidden" name="enrollmentId" value="<?= $enrollment['txn_id'] ?>">
                                    <input type="hidden" name="studentId" value="<?= $enrollment['student_id'] ?>">
                                    <select name="status">
                                        <?php foreach ($enrollmentStatuses as $status) : ?>
                                            <option value="<?= $status['id'] ?>" <?= $status['name'] === $enrollment['status'] ? 'selected' : '' ?>><?= $status['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        const selects = document.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', function(e) {
                const form = e.target.parentElement;
                form.submit();
            });
        });
    </script>
    
</html>