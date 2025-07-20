<?php
session_start();
if (isset($_POST["id"]) && isset($_POST["name"])) {
    $id = htmlspecialchars($_POST["id"]);
    $name = htmlspecialchars($_POST["name"]);
    require_once("conn.php");
    $req = "DELETE FROM books WHERE id=?";
    $ps = $pdo->prepare($req);
    $param = array($id);
    if ($ps->execute($param)) {
        $_SESSION['message'] = "$name book has been deleted from Rouen Library";
    } else {
        $_SESSION['error'] = "Error occurred, $name book was not deleted. Try again";
    }
    header("location:admin_page.php");
}
?>