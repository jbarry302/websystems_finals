<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AcademiaHub: Program List</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="assets/css/program-list.css">
    <script src="https://kit.fontawesome.com/a38ed59c98.js" crossorigin="anonymous"></script>
</head>

<body>


    <!-- <header> -->
    <?php include('inc/header.php'); ?>
    <!-- </header> -->

    <?php
    $qs = "SELECT
            p.id,
            p.image,
            p.title,
            p.description,
            p.code,
            p.total_units,
            p.years,
            p.semesters,
            COUNT(CASE WHEN eh.enrollment_status_id = 3 THEN 1 END) AS accepted_enrollees
        FROM
            programs p
            LEFT JOIN enrollment_history eh ON p.id = eh.program_id
        GROUP BY
            p.id, p.title, p.description, p.code, p.total_units, p.years, p.semesters
        ORDER BY
            accepted_enrollees DESC;
        ";
    $q = mysqli_query($conn, $qs);
    $programs = mysqli_num_rows($q) > 0 ? mysqli_fetch_all($q, MYSQLI_ASSOC) : [];
    ?>

    <main>
        <div class="container">
            <h1>Program List</h1>
            <div class="program-list">
                <?php foreach ($programs as $program) : ?>
                    <div class="program-item">
                        <h2><?php echo $program['title']; ?></h2>
                        <p><?php echo $program['description']; ?></p>
                        <div class="program-options">
                            <div class="program-details">
                                <span class="code"><?php echo $program['code']; ?></span>
                                <span class="units"><?php echo $program['total_units']; ?> units</span>
                                <span class="duration"><?php echo $program['years']; ?> years</span>
                                <span class="accepted_enrollees"><?php echo $program['accepted_enrollees']; ?> <i class="fa-solid fa-user" title="<?= $program['accepted_enrollees'] ?> enrollees"></i></span>
                            </div>
                            <?php if (isset($_SESSION['email']) && $_SESSION['role'] === 'student') : ?>
                                <div class="program-buttons">
                                    <a href="enrollment-form.php?code=<?= $program['code']; ?>" class="btn">Enroll Now!</a>
                                    <a href="program-prospectus.php?code=<?= $program['code'] ?>"><i class="fa-solid fa-circle-info fa-lg" title="view courses"></i></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>






    <!-- <footer> -->
    <?php include('inc/footer.php'); ?>
    <!-- </footer> -->

</body>

</html>