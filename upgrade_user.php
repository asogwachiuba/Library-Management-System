<?php
session_start();
if (isset($_POST["id"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    require_once("conn.php");
    $req = "UPDATE users
    SET role = 0
    WHERE id = ?;
    ";
    $ps = $pdo->prepare($req);
    $param = array($id);
    if ($ps->execute($param)) {
        $_SESSION['message'] = "$name is now an admin";
        header("location:admin_page.php");
    } else {
        $_SESSION['error'] = "Error occurred. $name is still a user";
        header("location:admin_page.php");
    }
}
?>