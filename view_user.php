<?php
session_start();
$title = "Rouen Library";
include 'role_admin.php';
include 'header.php';
include 'menu.php';
include 'message_popup.php';

require_once("conn.php");

// Retrieve user info
$user_id = $_POST['id'] ?? $_GET['id'] ?? null;

if (!$user_id) {
    die("User ID is required.");
}
try {
    // Fetch user information
    $userQuery = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $userQuery->execute([$user_id]);
    $user = $userQuery->fetch();

    if (!$user) {
        die("User not found.");
    }

    // Fetch borrowed books
    $search = $_GET['search'] ?? '';
    $bookQuery = $pdo->prepare("
    SELECT bb.*, b.title, b.author, b.photo, b.description 
    FROM books_borrowed bb 
    JOIN books b ON bb.book_id = b.id 
    WHERE bb.user_id = ? AND (b.title LIKE ? OR b.author LIKE ?)
    ORDER BY bb.date_borrowed DESC;
");
    $searchParam = "%$search%";
    $bookQuery->execute([$user_id, $searchParam, $searchParam]);
    $borrowedBooks = $bookQuery->fetchAll();
} catch (Exception $e) {
    die("Error: $e");
}


?>

<div class="container  mt-5">
    <!-- User Info -->
    <div class="card mb-4">
        <div class="card-body d-flex align-items-center">
            <img src="images/<?= htmlspecialchars($user['photo'], ENT_QUOTES, 'UTF-8') ?>" alt="User Photo"
                class="rounded-circle me-4" width="100" height="100">
            <div>
                <h3><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                <p>Email: <?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></p>
                <p>Role: <?= $user['role'] == 0 ? 'Admin' : 'User' ?></p>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <form method="get" class="mb-4">
        <input type="hidden" name="id" value="<?= $user_id ?>">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search borrowed books by title or author"
                value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" class="btn btn-primary"
                style="background-color: #5a189a; border-color: #5a189a;">Search</button>
        </div>
    </form>

    <!-- Borrowed Books -->
    <div class="card shadow border-0">
        <div class="card-header" style="background-color: #5a189a; color: #fff;">
            <h4 class="mb-0">Borrowed Books</h4>
        </div>


        <div class="card-body">
            <?php if ($borrowedBooks): ?>
                <table class="table table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Author</th>
                            <th scope="col">Borrow Date</th>
                            <th scope="col">Return Date</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($borrowedBooks as $book): ?>
                            <tr>
                                <td class="fw-bold"><?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($book['author'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($book['date_borrowed'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <?= $book['date_returned'] ?
                                        '<span class="badge bg-success">' . htmlspecialchars($book['date_returned'], ENT_QUOTES, 'UTF-8') . '</span>' :
                                        '<span class="badge bg-danger">Not Returned</span>' ?>
                                </td>
                                <td>
                                    <?php if (!$book['date_returned']): ?>
                                        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                            data-bs-target="#modal-borrow-<?= htmlspecialchars($book['book_id'], ENT_QUOTES, 'UTF-8') ?>-<?= htmlspecialchars($book['date_borrowed'], ENT_QUOTES, 'UTF-8') ?>">Return</button>
                                    <?php endif; ?>
                                </td>

                                <!-- Modal -->
                                <div class='modal fade'
                                    id='modal-borrow-<?= htmlspecialchars($book['book_id'], ENT_QUOTES, 'UTF-8') ?>-<?= htmlspecialchars($book['date_borrowed'], ENT_QUOTES, 'UTF-8') ?>'
                                    tabindex='-1'
                                    aria-labelledby='modalLabel-<?= htmlspecialchars($book['book_id'], ENT_QUOTES, 'UTF-8') ?>-<?= htmlspecialchars($book['date_borrowed'], ENT_QUOTES, 'UTF-8') ?>'
                                    aria-hidden='true'>
                                    <div class='modal-dialog modal-dialog-centered modal-lg'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title'
                                                    id='modalLabel-<?= htmlspecialchars($book['book_id'], ENT_QUOTES, 'UTF-8') ?>-<?= htmlspecialchars($book['date_borrowed'], ENT_QUOTES, 'UTF-8') ?>'>
                                                    Return <?= htmlspecialchars($book['title']) ?> To Rouen Library
                                                </h5>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal'
                                                    aria-label='Close'></button>
                                            </div>
                                            <div class='modal-body'>
                                                <p><strong><?= htmlspecialchars($user['name']) ?></strong> has returned this
                                                    book to the library today?
                                                </p>
                                            </div>
                                            <div class='modal-footer justify-content-between'>
                                                <button type='button' class='btn btn-secondary'
                                                    data-bs-dismiss='modal'>Close</button>
                                                <form action="return_book.php" method='post'>
                                                    <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                    <input type="hidden" name="borrowed_date"
                                                        value="<?= $book['date_borrowed'] ?>">
                                                    <input type="hidden" name="book_name" value="<?= $book['title'] ?>">
                                                    <input type="hidden" name="user_name" value="<?= $user['name'] ?>">
                                                    <button type='submit' class='btn btn-primary'>Book Returned</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info text-center" role="alert">
                    No borrowed books found.
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>