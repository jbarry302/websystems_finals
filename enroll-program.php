<?php

if($_SERVER['REQUEST_METHOD'] === 'POST') {

}

if(isset($_POST['code'])) {
    header('Location: enrollment-form.php?code=' . $_POST['code']);
    exit;
}

header('Location: program-list.php');
exit;
?>