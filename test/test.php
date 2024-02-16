<?php

// password = 12345678 :p
if (true) {
    header('Location: ../index.php');
    exit;
}


// do not access this file directly
// these are just query templates for insert automation


require_once('../config/db.php');
require_once('../config/config.php');



// inserts random data into the enrollment history
$rows = 100;
$accs = [1, 13];
$programIds = range(1, 10);
$courseIds = range(1, 64);
$enrollmentStatus = range(1, 5);
$year = range(1, 4);
$semester = range(1, 2);

$minStartDate = strtotime('2023-01-01');  // Minimum start date
$maxStartDate = strtotime('2023-12-31');  // Maximum start date



$qs = "INSERT INTO `enrollment_history`(`program_id`, `account_id`, `enrollment_status_id`, `curriculum_year`, `curriculum_semester`, `start_date`, `end_date`) VALUES ";
for ($i = 0; $i < $rows; $i++) {
    $randomStartDate = mt_rand($minStartDate, $maxStartDate);
    $start_date = date('Y-m-d', $randomStartDate);

    $minEndDate = strtotime('+15 days', $randomStartDate);
    $maxEndDate = strtotime('+30 days', $randomStartDate);
    $randomEndDate = mt_rand($minEndDate, $maxEndDate);
    $end_date = date('Y-m-d', $randomEndDate);

    $program_id = $programIds[array_rand($programIds)];
    $account_id = $accs[array_rand($accs)];
    $enrollment_status_id = $enrollmentStatus[array_rand($enrollmentStatus)];
    $curriculum_year = $year[array_rand($year)];
    $curriculum_semester = $semester[array_rand($semester)];

    if ($enrollment_status_id == 3 || $enrollment_status_id == 4) {
        $qs .= "('$program_id', '$account_id', '$enrollment_status_id', '$curriculum_year', '$curriculum_semester', '$start_date', '$end_date'),";
    } else {
        $qs .= "('$program_id', '$account_id', '$enrollment_status_id', '$curriculum_year', '$curriculum_semester', '$start_date', NULL),";
    }
}

$qs = rtrim($qs, ', ');
$qs .= ';';

echo $qs;




// inserts data into the prospectus table
// $qs = "SELECT `id`, `years`, `semesters` FROM `programs`;";
// $q = mysqli_query($conn, $qs);

// $programs = mysqli_num_rows($q) > 0 ? mysqli_fetch_all($q, MYSQLI_ASSOC) : null;
// echo "programs:<br>";
// echo json_encode($programs);
// echo "<br><br><br>";

// $programIds = array_map(function ($program) {
//     return $program['id'];
// }, $programs);

// $year = 4;
// $semester = 2;

// $qs = "SELECT `id` FROM `courses`;";
// $q = mysqli_query($conn, $qs);
// $courses = mysqli_num_rows($q) > 0 ? mysqli_fetch_all($q, MYSQLI_ASSOC) : null;
// echo "courses:<br>";
// echo json_encode($courses);
// echo "<br><br><br>";

// echo "courseIds:<br><div style='max-width: 500px; word-wrap: break-word; white-space: pre-wrap;'>";
// $courseIds = array_chunk($courses, 16); // Divide courses into chunks
// echo json_encode($courseIds);
// // var_dump($courseIds);
// echo "</div><br><br><br>";


// $q = "INSERT INTO `prospectus`(`program_id`, `course_id`, `year`, `semester`, `pre_requisite_course_id`) VALUES ";

// $index = 0;
// for ($i = 0; $i < count($programIds); $i++) {
//     $program = $programIds[$i];
//     $courses = $courseIds[$index];
//     $ii = 0;

//     for ($j = 1; $j <= $year; $j++) {
//         for ($k = 1; $k <= $semester; $k++) {
//             $c = $courses[$ii]['id'];
//             if ($k == 1) {
//                 $q .= "('$program', $c, $j, $k, NULL),";
//                 $ii++;
//                 $c = $courses[$ii]['id'];
//                 $q .= "('$program', $c, $j, $k, NULL),";
//                 $ii++;
//             } else {
//                 $iii = $ii - 1;
//                 $cc = $courses[$iii]['id'];
//                 $q .= "('$program', $c, $j, $k, $cc),";
//                 $ii++;
//                 $iii = $ii - 1;
//                 $c = $courses[$ii]['id'];
//                 $cc = $courses[$iii]['id'];
//                 $q .= "('$program', $c, $j, $k, $cc),";
//                 $ii++;
//             }
            
//         }
        
//     }

//     $index = $index + 1 >= count($courseIds) ? 0 : $index + 1;
// }

// $q = rtrim($q, ', ');
// $q .= ';';

// echo "<br>final query:<br><br><br>" . $q;
