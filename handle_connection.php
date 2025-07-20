<?php
session_start(); // Start the session

// Debugging function to log to browser console
function console_log($data)
{
    echo "<script>console.log(" . json_encode($data) . ");</script>";
}

// Form Content:
$email = htmlentities($_POST['email']);
$password = htmlentities($_POST['password']);
$hashedPassword = md5($password); // Hash the password

// Debugging: Log form inputs
console_log("Email: $email");
console_log("Password (before hashing): $password");
console_log("Password (hashed): $hashedPassword");

// Connection:
require_once("conn.php");

try {
    $req = "SELECT * FROM users WHERE email = ? AND pwd = ?";
    $ps = $pdo->prepare($req);
    $params = array($email, $hashedPassword);
    // Execute the query
    $ps->execute($params);

    if ($row = $ps->fetch()) {
        // Redirection to the welcome Page
        $_SESSION['message'] = "Login Successful";
        $_SESSION['profile'] = $row;
        header('Location: ' . ($row['role'] === 0 ? 'admin_page.php' : 'user_page.php'));
        exit();
    } else {
        $_SESSION['error'] = "Wrong Email or Password";
        header('Location: connection.php');
        exit;
    }
} catch (PDOException $e) {
    // Debugging: Log database errors
    console_log("Database Error: " . $e->getMessage());
    error_log("Database Error: " . $e->getMessage());

    $_SESSION['error'] = "Database error occurred. Please try again.";
    header('Location: connection.php');
    exit;
}
