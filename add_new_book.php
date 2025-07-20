<?php
session_start();
try {
    // Sanitize input data
    $title = htmlentities($_POST['title']);
    $author = htmlentities($_POST['author']);
    $available_copies = htmlentities($_POST['available_copies']);
    $description = htmlentities($_POST['description']);
    $photo = $_FILES['photo']['name'];
    $fichierTemp = $_FILES['photo']['tmp_name'];
    move_uploaded_file($fichierTemp, './images/' . $photo);

    // Database update
    require_once("conn.php");
    $req = "INSERT INTO books (title, author, available_copies, photo, description) 
    VALUES (?, ?, ?, ?, ?);
    ";
    $ps = $pdo->prepare($req);
    $params = array($title, $author, $available_copies, $photo, $description);
    $ps->execute($params);

    // Success message
    $_SESSION['message'] = $title . " has been added to Rouen library";
    header("location:admin_page.php");
} catch (Exception $e) {
    echo "Error updating $title details: " . $e->getMessage();
}
?>