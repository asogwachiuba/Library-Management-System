<?php
session_start();
if (!(isset($_SESSION['profile']))) {
    $_SESSION['error'] = 'Only logged in user can access this page. Login with your registered account';
    header('Location: index.php');
    exit;
}
if (isset($_POST['book_id'])) {
    // Get user ID from session
    $user_id = $_SESSION['profile']['id'];
    // Get book id
    $book_id = intval($_POST['book_id']);
    $available_copies = intval($_POST['available_copies']);
    $book_title = htmlentities($_POST['book_title']);
    // Checking book availability
    if ($available_copies <= 0) {
        $_SESSION['error'] = "No copy of $book_title is available";
        header('Location: user_page.php');
        exit;
    }

    require_once 'conn.php';
    try {
        // Database transaction
        $pdo->beginTransaction();

        // Check if the user has reached maximum borrowing limit without returning
        $stmt = $pdo->prepare("SELECT COUNT(*) AS active_borrows FROM books_borrowed WHERE user_id = ? AND is_returned = 0");
        $params1 = array($user_id);
        $stmt->execute($params1);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['active_borrows'] >= 3) {
            $_SESSION['error'] = "You have reached the maximum borrowing limit of 3 books. Please return a book before borrowing another.";
            $pdo->rollBack();
            header('Location: user_page.php');
            exit;
        }

        // Check if the user has already borrowed this book and has not returned it
        $stmt = $pdo->prepare("SELECT * FROM books_borrowed WHERE book_id = ? AND user_id = ? AND is_returned = 0");
        $params2 = array($book_id, $user_id);
        $stmt->execute($params2);
        $existingBorrow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingBorrow) {
            // If the user has already borrowed this book and not returned it
            echo "";
            $pdo->rollBack();
            $_SESSION['error'] = "You have already borrowed $book_title and not returned it yet.";
            header('Location: user_page.php');
            exit;
        }


        // Insert into borrowed_books table
        $stmt = $pdo->prepare("INSERT INTO books_borrowed (date_borrowed, book_id, user_id, is_returned) VALUES (NOW(), ?, ?, 0)");
        $params3 = array($book_id, $user_id);
        $stmt->execute($params3);

        // Decrement the available copies
        $stmt = $pdo->prepare("UPDATE books SET available_copies = available_copies - 1 WHERE id = ?");
        $params4 = array($book_id);
        $stmt->execute($params4);

        // Saves the updates to the database permanently
        $pdo->commit();
        $_SESSION['message'] = "$book_title has been successfully borrowed";
        header('Location: book_history.php');
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }

} else {
    header('Location: user_page.php');
    exit;
}
// 