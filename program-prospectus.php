<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AcademiaHub: Prospectus</title>
    <link rel="stylesheet" href="assets/css/program-prospectus.css">
</head>

<body>
    <?php
    include('inc/header.php');
    if (isset($_GET['code'])) {
        $code = $_GET['code'];
        $qs = "SELECT * FROM `programs` WHERE `code` = '$code'";
        $q = mysqli_query($conn, $qs);

        $program = mysqli_num_rows($q) > 0 ? mysqli_fetch_assoc($q) : null;
        if (!$program) {
            $error_message = "Program not found!";
        }
    } else {
        header('Location: program-list.php');
    }

    include('inc/binary-toast.php');
    if (!$program) {
        echo '<script>
        setTimeout(function() {
            window.location.href = "program-list.php";
        }, 3500);
    </script>';
        exit;
    } else {
        $qs = "SELECT 
          p.code AS program,
          c.name AS course_title,
          c.code AS course_code,
          pr.year,
          pr.semester,
          pc.code AS prerequisite_code,
          c.units AS units
        FROM
          prospectus pr
          INNER JOIN programs p ON pr.program_id = p.id
          INNER JOIN courses c ON pr.course_id = c.id
          LEFT JOIN courses pc ON pr.pre_requisite_course_id = pc.id
        WHERE
          p.code = '$code';";

        $q = mysqli_query($conn, $qs);
        $prospectus = mysqli_num_rows($q) > 0 ? mysqli_fetch_all($q, MYSQLI_ASSOC) : [];

        $years = []; // Array to store the units for each year
        // Loop through the prospectus results to calculate total units for each year
        foreach ($prospectus as $course) {
            $year = $course['year'];
            $units = $course['units'];

            // Check if the year is already present in the array
            if (!isset($years[$year])) {
                $years[$year] = 0;
            }

            // Increment the total units for the year
            $years[$year] += $units;
        }

    ?>

        <main>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>Course Title</th>
                            <th>Course Code</th>
                            <th>Year</th>
                            <th>Semester</th>
                            <th>Prerequisite Code</th>
                            <th>Units</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalUnits = 0;
                        $currentYear = null;
                        $yearlyTotalUnits = 0;

                        foreach ($prospectus as $course) {
                            $program = $course['program'];
                            $courseTitle = $course['course_title'];
                            $courseCode = $course['course_code'];
                            $year = $course['year'];
                            $semester = $course['semester'];
                            $prerequisiteCode = $course['prerequisite_code'];
                            $units = $course['units'];

                            // Calculate yearly total units
                            if ($year !== $currentYear) {
                                if ($currentYear !== null) {
                                    echo "<tr class='yearly-total'>
                                            <td colspan='6'>Total Units for Year $currentYear</td>
                                            <td>$yearlyTotalUnits</td>
                                        </tr>";
                                    echo "<tr class='empty-row'>
                                        <td colspan='7'></td>
                                    </tr>";
                                }

                                $currentYear = $year;
                                $yearlyTotalUnits = 0;
                            }

                            echo "<tr>
                    <td>$program</td>
                    <td>$courseTitle</td>
                    <td>$courseCode</td>
                    <td>$year</td>
                    <td>$semester</td>
                    <td>$prerequisiteCode</td>
                    <td>$units</td>
                  </tr>";

                            // Accumulate total units
                            $totalUnits += $units;
                            $yearlyTotalUnits += $units;
                        }

                        // Display overall total units
                        if ($currentYear !== null) {
                            echo "<tr class='yearly-total'>
                                    <td colspan='6'>Total Units for Year $currentYear</td>
                                    <td>$yearlyTotalUnits</td>
                                </tr>";
                            echo "<tr class='empty-row'>
                                <td colspan='7'></td>
                            </tr>";
                        }

                        echo "<tr class='overall-total'>
                                <td colspan='6'>Overall Total Units</td>
                                <td>$totalUnits</td>
                            </tr>";

                        ?>
                    </tbody>
                </table>
            </div>

            <div class="enroll-button">
                <a href="enrollment-form.php?code=<?=$program?>" class="btn">Enroll</a>
            </div>
        </main>


    <?php } ?>

</body>

</html>