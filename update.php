<?php
session_start();
$id = htmlentities($_POST['id']);
$name = htmlentities($_POST['name']);
$email = htmlentities($_POST['email']);
if (!empty($_POST['password'])) {
    $password = md5($_POST['password']); // Hash the new password
} else {
    $password = htmlentities($_SESSION['password']); // Keep the existing password
}

$role = 1; // 1 for a simple user and 0 for an admin

// Determine which photo to use
if (!empty($_FILES['new_photo']['name'])) {
    // Get the file extension of the uploaded photo
    $photoExtension = pathinfo($_FILES['new_photo']['name'], PATHINFO_EXTENSION); // Extracts file extension (e.g., jpg, png, etc.)
    $emailWithoutExtension = preg_replace('/\.[^.]+$/', '', $email); // Remove the extension from the email
    $newPhoto = $emailWithoutExtension . '.' . $photoExtension; // Create new photo name using the modified email and extension

    $fichierTemp = $_FILES['new_photo']['tmp_name']; // Correct the input field name here

    if (!empty($_FILES['new_photo']['name'])) {
        // Get the file extension of the uploaded photo
        $photoExtension = pathinfo($_FILES['new_photo']['name'], PATHINFO_EXTENSION); // Extracts file extension (e.g., jpg, png, etc.)
        $emailWithoutExtension = preg_replace('/\.[^.]+$/', '', $email); // Remove the extension from the email
        $newPhoto = $emailWithoutExtension . '.' . $photoExtension; // Create new photo name using the modified email and extension

        $fichierTemp = $_FILES['new_photo']['tmp_name']; // Temporary file location

        // Path to the folder where photos are stored
        $imagesFolder = './images/';
        $currentPhoto = $imagesFolder . $_POST['current_photo']; // Full path to the existing photo

        // Check if the current photo exists and delete it
        if (file_exists($currentPhoto) && is_file($currentPhoto)) {
            unlink($currentPhoto); // Delete the existing photo
        }

        // Move the uploaded file to the folder 'images' with the new name
        if (move_uploaded_file($fichierTemp, $imagesFolder . $newPhoto)) {
            // File successfully uploaded; update the photo name
            $photo = $newPhoto;
        } else {
            // Handle the error if the file upload fails
            die('Failed to save the uploaded photo. Please check permissions or the file path.');
        }
    } else {
        // No new photo uploaded; use the current photo
        $photo = htmlentities($_POST['current_photo']);
    }

} else {
    // No new photo uploaded; use the current photo
    $photo = htmlentities($_POST['current_photo']);
}


require_once("conn.php");

// Update the database with the new photo name
$req = "UPDATE users SET name=?, email=?, ";
if (isset($password)) {
    $req .= "pwd=?, ";
}
$req .= "photo=? WHERE id=?";
$ps = $pdo->prepare($req);
$params = isset($password) ? array($name, $email, $password, $photo, $id) : array($name, $email, $photo, $id);
$ps->execute($params);
// updating the local profile data
$_SESSION['profile']['name'] = $name;
$_SESSION['profile']['photo'] = $photo;
header("location:listusers.php");
?>