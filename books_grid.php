<?php
session_start();
if (!(isset($_SESSION['profile']))) {
    $_SESSION['error'] = 'Only logged in user can access this page. Login with your registered account';
    header('Location: index.php');
    exit;
}
require_once 'conn.php';
?>

<div class="grid-container">
    <?php
    try {
        $sql = "SELECT id, title, author, description, photo, available_copies FROM books";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $bookId = htmlspecialchars($row['id']); // Unique identifier
                $title = htmlspecialchars($row['title']);
                $author = htmlspecialchars($row['author']);
                $description = htmlspecialchars($row['description']);
                $photo = htmlspecialchars($row['photo']);
                $availableCopies = htmlspecialchars($row['available_copies']);

                // Grid item with modal trigger
                echo "<div class='book-card' data-bs-toggle='modal' data-bs-target='#modal-$bookId' style='cursor: pointer;'>";
                echo "<img src='images/$photo' alt='$title'>";
                echo "<h3>$title</h3>";
                echo "<p><strong>Author:</strong> $author</p>";
                echo "<p>$description</p>";
                echo "<p><strong>Available Copies:</strong> $availableCopies</p>";
                echo "</div>";

                // Modal Pop-up
                echo "
                <div class='modal fade' id='modal-$bookId' tabindex='-1' aria-labelledby='modalLabel-$bookId' aria-hidden='true'>
                    <div class='modal-dialog modal-dialog-centered modal-lg'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='modalLabel-$bookId'>$title</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <div class='modal-body'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <img src='images/$photo' alt='$title' class='img-fluid rounded shadow-sm'>
                                    </div>
                                    <div class='col-md-8'>
                                        <p><strong>Author:</strong> $author</p>
                                        <p><strong>Description:</strong> $description</p>
                                        <p><strong>Available Copies:</strong> $availableCopies</p>
                                    </div>
                                </div>
                            </div>
                            <div class='modal-footer justify-content-between'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                <form action='borrow_book.php' method='post'>
                                 <input type='hidden' name='book_id' value='$bookId'>
                                 <input type='hidden' name='available_copies' value='$availableCopies'>
                                 <input type='hidden' name='book_title' value='$title'>
                                 <button type='submit' class='btn btn-primary'>Confirm Borrow</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "No books found in the database.";
        }
    } catch (PDOException $e) {
        echo "Error fetching data: " . $e->getMessage();
    }


    ?>
</div>