<?php
session_start();
if (
    isset($_POST["book_id"])
    && isset($_POST["user_id"])
    && isset($_POST["borrowed_date"])
    && isset($_POST["user_name"])
    && isset($_POST["book_name"])
) {
    $bookId = $_POST["book_id"];
    $userId = $_POST["user_id"];
    $borrowedDate = $_POST["borrowed_date"];
    $userName = $_POST["user_name"];
    $bookName = $_POST["book_name"];

    try {
        require_once("conn.php");
        // Database transaction
        $pdo->beginTransaction();
        $req = "UPDATE books_borrowed
                SET is_returned = 1, date_returned = NOW()
                WHERE book_id = ? 
                AND user_id = ?
                AND date_borrowed = ?;";
        $ps = $pdo->prepare($req);
        $params = array($bookId, $userId, $borrowedDate);
        $ps->execute($params);

        // Increment the available copies of borrowed book returned
        $stmt = $pdo->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE id = ?");
        $param = array($bookId);
        $stmt->execute($param);

        // Saves the updates to the database permanently
        $pdo->commit();

        // After completing the transaction
        $_SESSION['message'] = "$userName has returned $bookName to Rouen Library";


        if (isset($_POST["location"])) {
            // Redirecting to the current book details page
            header("location:" . $_POST['location'] . "?id=" . $bookId);
        } else {
            // Redirect to the previous page, passing the user_id as a query parameter
            $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'view_user.php';
            $redirect_url = strpos($redirect_url, '?') === false ? $redirect_url . "?id=" . $userId : $redirect_url . "&id=" . $userId;
            header("Location: " . $redirect_url);
        }
    } catch (Exception $e) {
        error_log("Error returning book: " . $e->getMessage());  // Log to server log
        $_SESSION['error'] = "An unexpected error occurred. Please try again later.";
        header("Location: admin_page.php");
        exit;
    }
}
?>