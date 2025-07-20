<?php
session_start();
if (!(isset($_SESSION['profile']))) {
    $_SESSION['error'] = 'Only logged in user can access this page. Login with your registered account';
    header('Location: index.php');
    exit;
}
$user = $_SESSION['profile'];
$title = "Rouen Library";
include 'header.php';
include 'menu.php';
include 'message_popup.php';
include 'books_grid.php';
include 'footer.php';
?>