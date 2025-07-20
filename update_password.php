<?php
session_start();
require 'conn.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = htmlentities(trim($_POST['name']));
    $email = htmlentities(trim($_POST['email']));
    $newPassword = htmlentities(trim($_POST['new_password']));
    $confirmPassword = htmlentities(trim($_POST['confirm_password']));

    // Validate input fields
    if (empty($name) || empty($email) || empty($newPassword) || empty($confirmPassword)) {
        die('All fields are required.');
    }

    if ($newPassword !== $confirmPassword) {
        die('Passwords do not match.');
    }

    // Hash the new password
    $hashedPassword = md5($newPassword);

    try {
        // Prepare SQL query to update the password
        $query = "UPDATE users SET pwd = ? WHERE name = ? AND email = ?";
        $stmt = $pdo->prepare($query);


        $params = array($hashedPassword, $name, $email, );

        // Execute the query
        if ($stmt->execute($params)) {
            $_SESSION['message'] = "Password update successful. Login to proceed";
            header('Location: connection.php');
        } else {
            $_SESSION['message'] = "Failed to update password. Please try again";
            header('Location: connection.php');
        }
    } catch (PDOException $e) {
        // Handle any errors
        die('Error: ' . $e->getMessage());
    }
} else {
    die('Invalid request method.');
}
