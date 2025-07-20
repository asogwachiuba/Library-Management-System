<?php
session_start();
if (isset($_POST["id"])) {
    $id = $_POST["id"];
    require_once("conn.php");
    $req = "DELETE FROM users WHERE id=?";
    $ps = $pdo->prepare($req);
    $param = array($id);
    if ($ps->execute($param)) {
        $_SESSION['message'] = "The user has been deleted";
        header("location:admin_page.php");
    } else {
        $_SESSION['error'] = "The user was not deleted";
        header("location:admin_page.php");
    }
}
?>