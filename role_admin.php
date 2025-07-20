<?php
session_start();
if (!(isset($_SESSION['profile']))) {
    $_SESSION['error'] = "You need to log in and be admin to access this page";
    header('Location: connection.php');
    exit;
}

if ($_SESSION['profile']['role'] === 1) {
    $_SESSION['error'] = "You need to be an admin to access this page";
    header('Location: user_page.php');
    exit;
}
