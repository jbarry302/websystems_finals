<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AcademiaHub</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- php -->
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Access the user account values from the session variables
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $role = $_SESSION['role'];
    }
    ?>

    <?php
    require_once('config/db.php');
    require_once('config/config.php');


    // $qs = "SELECT * FROM `top_accepted_enrollment_programs` LIMIT 3";
    $qs = "SELECT * FROM `top_accepted_enrollment_programs`";
    $q = mysqli_query($conn, $qs);
    $programs = mysqli_num_rows($q) > 0 ? mysqli_fetch_all($q, MYSQLI_ASSOC) : [];

    $qs = "SELECT * FROM key_features";
    $q = mysqli_query($conn, $qs);
    $keyFeatures = mysqli_num_rows($q) > 0 ? mysqli_fetch_all($q, MYSQLI_ASSOC) : [];

    ?>

    <!-- <header> -->
    <?php include('inc/header.php'); ?>
    <!-- </header> -->

    <main>
        <div class="main-container">
            <section id="banner">
                <div class="banner-container">
                    <h2>Welcome to AcademiaHub</h2>
                    <p>Learn, Grow, Succeed!</p>
                </div>
            </section>


            <!-- courses -->
            <section id="courses">
                <div class="courses-container">
                    <h2>Top Enrolled Programs
                        <a href="program-list.php">
                            <i class="fa-solid fa-arrow-right fa-shake" style="color: skyblue;"></i>
                        </a>
                    </h2>
                    <div class="course-cards">
                        <?php foreach ($programs as $program) : ?>
                            <?php
                            if (!empty($program['image'])) {
                                try {
                                    $image = base64_encode($program['image']);
                                    $image = 'data:image/jpeg;base64,' . $image;
                                } catch (Exception $e) {
                                    $image = 'https://via.placeholder.com/300?text=' . $program['code'];
                                }
                            } else {
                                $image = 'https://via.placeholder.com/300?text=' . $program['code'];
                            }
                            ?>
                            <div class="course-card">
                                <div class="course-thumbnail">
                                    <img src="<?= $image ?>" alt="Course 1">
                                </div>
                                <div class="course-details">
                                    <h3><?= $program['title'] ?>(<?= $program['code'] ?>)</h3>
                                    <p><?= $program['description'] ?></p>
                                    <div class="enrollees-badge">
                                        <span class="enrollees-count"><?= $program['accepted_enrollees'] ?></span>
                                        <span class="enrollees-label">Enrollees</span>
                                    </div>
                                    <a href="program-prospectus.php?code=<?= $program['code'] ?>" class="btn">View</a>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </section>




            <!-- features -->
            <section id="features" class="animate-section">
                <div class="features-container">
                    <h2>Key Features</h2>
                    <div class="feature-cards">
                        <?php foreach ($keyFeatures as $keyFeature) : ?>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="<?= $keyFeature['icon'] ?>"></i>
                                </div>
                                <h3><?= $keyFeature['title'] ?></h3>
                                <p><?= $keyFeature['description'] ?></p>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </section>


        </div>
    </main>


    <!-- <footer> -->
    <?php include('inc/footer.php'); ?>
    <!-- <footer/> -->


</body>

</html>