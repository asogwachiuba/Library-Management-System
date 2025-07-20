<?php
session_start();
try {
    // Sanitize input data
    $id = htmlentities($_POST['id']);
    $title = htmlentities($_POST['title']);
    $author = htmlentities($_POST['author']);
    $available_copies = htmlentities($_POST['available_copies']);
    $description = htmlentities($_POST['description']);
    $currentPhoto = htmlentities($_POST['current_photo']); // Hidden field for the current photo
    $newPhoto = $_FILES['new_photo']['name'] ?? null; // New uploaded photo, if provided

    // Determine which photo to use
    if (!empty($newPhoto)) {
        // A new photo is uploaded; process the file
        $photo = $newPhoto;
        $fichierTemp = $_FILES['new_photo']['tmp_name'];
        move_uploaded_file($fichierTemp, './images/' . $photo);
    } else {
        // No new photo uploaded; use the current photo
        $photo = $currentPhoto;
    }

    // Database update
    require_once("conn.php");
    $req = "UPDATE books SET title=?, author=?, available_copies=?, photo=?, description=? WHERE id=?";
    $ps = $pdo->prepare($req);
    $params = array($title, $author, $available_copies, $photo, $description, $id);
    $ps->execute($params);

    // Success message
    $_SESSION['message'] = $title . " has been updated successfully";
    isset($_POST['location']) ? header("location:" . $_POST['location'] . "?id=" . $id) :
        header("location:admin_page.php");
} catch (Exception $e) {
    echo "Error updating $title details: " . $e->getMessage();
}
?>