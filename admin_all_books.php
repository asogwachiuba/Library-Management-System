<?php
session_start();
$title = "Rouen Library";
include 'role_admin.php';
include 'header.php';
include 'menu.php';
include 'message_popup.php';
// Fetch book data from the database
require_once("conn.php");
$query = "SELECT id, title, author, available_copies, photo, description FROM books";
$stmt = $pdo->query($query);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total number of books
$totalBooks = count($books);
?>

<div class="container">
    <h1 class="text-center my-4">Available Books</h1>
    <div class="total-books">
        Total Books: <?= htmlspecialchars($totalBooks, ENT_QUOTES, 'UTF-8') ?>
    </div>
    <div class="book-grid">
        <?php foreach ($books as $book): ?>
            <div class="admin-book-card"
                onclick="location.href='admin_book_details.php?id=<?= htmlspecialchars($book['id'], ENT_QUOTES, 'UTF-8') ?>'">
                <img src="images/<?= htmlspecialchars($book['photo'], ENT_QUOTES, 'UTF-8') ?>"
                    alt="<?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?> Cover">
                <div class="admin-book-card-body">
                    <h5><?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?></h5>
                    <p><strong>Author:</strong> <?= htmlspecialchars($book['author'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Copies:</strong> <span
                            class="availability"><?= htmlspecialchars($book['available_copies'], ENT_QUOTES, 'UTF-8') ?></span>
                    </p>
                    <p><strong>Description:</strong>
                        <?= htmlspecialchars(substr($book['description'], 0, 50), ENT_QUOTES, 'UTF-8') ?>...</p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>