<?php
session_start();
$title = "Rouen Library";
include 'role_admin.php';
include 'header.php';
include 'menu.php';
include 'message_popup.php';

try {
    require_once('conn.php');

    // Get the book ID
    $bookId = $_GET['id'] ?? null;

    if (!$bookId) {
        throw new Exception("Book ID is required.");
    }

    // Fetch book information
    $bookInfoQuery = "SELECT * FROM books WHERE id = ?";
    $bookStmt = $pdo->prepare($bookInfoQuery);
    $bookStmt->execute([$bookId]);
    $bookInfo = $bookStmt->fetch();

    if (!$bookInfo) {
        throw new Exception("Book not found.");
    }

    // Handle search query
    $search = $_GET['search'] ?? null;

    // Fetch users who borrowed the book
    $borrowedQuery = "
    SELECT 
        bb.book_id, 
        u.name AS user_name,
        bb.user_id,
        bb.date_borrowed, 
        bb.date_returned 
    FROM books_borrowed bb 
    INNER JOIN users u ON bb.user_id = u.id 
    WHERE bb.book_id = ?";

    if ($search) {
        $borrowedQuery .= " AND u.name LIKE ?";
        $borrowedStmt = $pdo->prepare($borrowedQuery);
        $borrowedStmt->execute([$bookId, "%$search%"]);
    } else {
        $borrowedStmt = $pdo->prepare($borrowedQuery);
        $borrowedStmt->execute([$bookId]);
    }

    $borrowedUsers = $borrowedStmt->fetchAll();
} catch (Exception $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<div class="container my-5">
    <div class="card mb-4 shadow-lg">
        <div class="card-header text-white" style="background: linear-gradient(90deg, #5a189a, #9d4edd);">
            <h3 class="mb-0">Book Information</h3>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <p class="text-danger text-center fw-bold"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
            <?php else: ?>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="position-relative">
                            <img src="images/<?= htmlspecialchars($bookInfo['photo'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($bookInfo['title'], ENT_QUOTES, 'UTF-8') ?> Cover"
                                class="img-fluid rounded shadow-sm"
                                style="width: 100%; max-height: 320px; object-fit: cover; transition: transform 0.3s ease;">
                            <div
                                class="position-absolute bottom-0 start-0 bg-dark bg-opacity-75 text-white p-1 px-2 rounded-end">
                                <small>Available Copies:
                                    <?= htmlspecialchars($bookInfo['available_copies'], ENT_QUOTES, 'UTF-8') ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4 class="purple-text welcome fw-bold mb-3">
                            <?= htmlspecialchars($bookInfo['title'], ENT_QUOTES, 'UTF-8') ?>
                        </h4>
                        <p class="mb-2"><strong>Author:</strong> <span
                                class="text-dark"><?= htmlspecialchars($bookInfo['author'], ENT_QUOTES, 'UTF-8') ?></span>
                        </p>
                        <p class="mb-3"><strong>Description:</strong></p>
                        <p class="text-muted lh-lg"><?= htmlspecialchars($bookInfo['description'], ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <div><button class="btn btn-sm btn-danger action-btn me-2" data-bs-toggle="modal"
                                data-bs-target="#book-modal-<?= htmlspecialchars($bookInfo['id'], ENT_QUOTES, 'UTF-8') ?>">Delete</button>
                            <button class="btn btn-sm btn-success action-btn me-2" data-bs-toggle="modal"
                                data-bs-target="#editModal-<?= $bookInfo['id'] ?>">Edit</button>
                        </div>

                        <!-- Delete modal -->
                        <div class='modal fade' id='book-modal-<?= htmlspecialchars($bookInfo['id'], ENT_QUOTES, 'UTF-8') ?>'
                            tabindex='-1'
                            aria-labelledby='modalLabel-<?= htmlspecialchars($bookInfo['id'], ENT_QUOTES, 'UTF-8') ?>'
                            aria-hidden='true'>
                            <div class='modal-dialog modal-dialog-centered modal-lg'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='modalLabel-$bookId'>Confirm Delete</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal'
                                            aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body'>
                                        <p>Are you sure, you want to
                                            delete <strong><?= htmlspecialchars($bookInfo['title']) ?></strong>
                                            account. All information regarding
                                            <?= htmlspecialchars($bookInfo['title']) ?> will be lost.
                                        </p>
                                    </div>
                                    <div class='modal-footer justify-content-between'>
                                        <button type='button' class='btn btn-secondary'
                                            data-bs-dismiss='modal'>Close</button>
                                        <form action="delete_book.php" method='post'>
                                            <input type="hidden" name="id" value=<?= $bookInfo['id'] ?>>
                                            <input type="hidden" name="locaton" value='admin_book_details'>
                                            <input type="hidden" name="name" value=<?= $bookInfo['title'] ?>>
                                            <button type='submit' class='btn btn-primary'>Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal-<?= $bookInfo['id'] ?>" tabindex="-1"
                            aria-labelledby="editModalLabel-<?= $bookInfo['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel-<?= $bookInfo['id'] ?>">Edit
                                            <?= $bookInfo['title'] ?> Details
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!--Form fields -->
                                        <form action="edit_book.php" method="POST" enctype="multipart/form-data">
                                            <!-- Hidden field for Book ID -->
                                            <input type="hidden" name="id" value="<?= $bookInfo['id'] ?>">
                                            <!-- Hidden field for knowing the location to return to -->
                                            <input type="hidden" name="location" value='admin_book_details.php'>
                                            <!-- Book Title -->
                                            <div class="mb-3">
                                                <label for="bookTitle-<?= $bookInfo['id'] ?>" class="form-label"
                                                    style="color: #212529;">
                                                    <strong>Book Title</strong>
                                                </label>
                                                <input type="text" class="form-control" id="bookTitle-<?= $bookInfo['id'] ?>"
                                                    name="title"
                                                    value="<?= htmlspecialchars($bookInfo['title'], ENT_QUOTES, 'UTF-8') ?>">
                                            </div>
                                            <!-- Book Author -->
                                            <div class="mb-3">
                                                <label for="bookAuthor-<?= $bookInfo['id'] ?>" class="form-label"
                                                    style="color: #212529;">
                                                    <strong>Book Author</strong>
                                                </label>
                                                <input type="text" class="form-control" id="bookAuthor-<?= $bookInfo['id'] ?>"
                                                    name="author"
                                                    value="<?= htmlspecialchars($bookInfo['author'], ENT_QUOTES, 'UTF-8') ?>">
                                            </div>
                                            <!-- Book Photo -->
                                            <div class="mb-3 d-flex align-items-center">
                                                <div>
                                                    <label for="bookPhoto-<?= $bookInfo['id'] ?>" class="form-label"
                                                        style="color: #212529;">
                                                        <strong>Book Photo</strong>
                                                    </label>
                                                    <!-- File input for new photo -->
                                                    <input type="file" class="form-control" id="bookPhoto-<?= $bookInfo['id'] ?>"
                                                        name="new_photo">

                                                    <!-- Hidden input to hold the current photo -->
                                                    <input type="hidden" name="current_photo"
                                                        value="<?= htmlspecialchars($bookInfo['photo'], ENT_QUOTES, 'UTF-8') ?>">

                                                    <small class="text-muted">Current Photo:
                                                        <?= htmlspecialchars($bookInfo['photo'], ENT_QUOTES, 'UTF-8') ?></small>
                                                </div>
                                                <div class="ms-3">
                                                    <!-- Display Current Photo -->
                                                    <img src="images/<?= htmlspecialchars($bookInfo['photo'], ENT_QUOTES, 'UTF-8') ?>"
                                                        alt="Book Photo" class="img-thumbnail"
                                                        style="max-width: 150px; max-height: 150px;">
                                                    <small class="d-block text-muted mt-1">Current Photo</small>
                                                </div>
                                            </div>
                                            <!-- Available Copies -->
                                            <div class="mb-3">
                                                <label for="availableCopies-<?= $bookInfo['id'] ?>" class="form-label"
                                                    style="color: #212529;">
                                                    <strong>Available Copies</strong>
                                                </label>
                                                <input type="number" class="form-control"
                                                    id="availableCopies-<?= $bookInfo['id'] ?>" name="available_copies"
                                                    value="<?= htmlspecialchars($bookInfo['available_copies'], ENT_QUOTES, 'UTF-8') ?>">
                                            </div>
                                            <!-- Description -->
                                            <div class="mb-3">
                                                <label for="bookDescription-<?= $bookInfo['id'] ?>" class="form-label"
                                                    style="color: #212529;">
                                                    <strong>Description</strong>
                                                </label>
                                                <textarea class="form-control" id="bookDescription-<?= $bookInfo['id'] ?>"
                                                    name="description"
                                                    value="<?= htmlspecialchars($bookInfo['description'], ENT_QUOTES, 'UTF-8') ?>"
                                                    rows="4"><?= htmlspecialchars($bookInfo['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
                                            </div>

                                            <!-- Submit Button -->
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>



    <?php if (!isset($error)): ?>
        <div class="card">
            <div class="card-header custom-card-header">
                <h4 class="mb-0">Borrowing Information</h4>
            </div>
            <div class="card-body">
                <form method="get" class="mb-3 d-flex">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($bookId, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search user by name"
                        value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit" class="btn btn-primary" style="background-color: #5a189a; border-color: #5a189a;">Search</button>
                </form>

                <?php if ($borrowedUsers): ?>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">User Name</th>
                                <th scope="col">Borrow Date</th>
                                <th scope="col">Return Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($borrowedUsers as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($user['date_borrowed'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <?= $user['date_returned'] ? htmlspecialchars($user['date_returned'], ENT_QUOTES, 'UTF-8') : '<span class="text-danger">Not Returned</span>' ?>
                                    </td>
                                    <td>
                                        <?php if (!$user['date_returned']): ?>
                                            <button type="submit" class="btn btn-sm return-btn" data-bs-toggle="modal"
                                            data-bs-target="#modal-borrow-<?= htmlspecialchars($bookInfo['id'], ENT_QUOTES, 'UTF-8') ?>-<?= htmlspecialchars($user['date_borrowed'], ENT_QUOTES, 'UTF-8') ?>">Return</button>
                                        <?php endif; ?>
                                    </td>
                                    <!-- Return Modal -->
                                <div class='modal fade'
                                    id='modal-borrow-<?= htmlspecialchars($bookInfo['id'], ENT_QUOTES, 'UTF-8') ?>-<?= htmlspecialchars($user['date_borrowed'], ENT_QUOTES, 'UTF-8') ?>'
                                    tabindex='-1'
                                    aria-labelledby='modalLabel-<?= htmlspecialchars($book['book_id'], ENT_QUOTES, 'UTF-8') ?>-<?= htmlspecialchars($user['date_borrowed'], ENT_QUOTES, 'UTF-8') ?>'
                                    aria-hidden='true'>
                                    <div class='modal-dialog modal-dialog-centered modal-lg'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title'
                                                    id='modalLabel-<?= htmlspecialchars($bookInfo['id'], ENT_QUOTES, 'UTF-8') ?>-<?= htmlspecialchars($user['date_borrowed'], ENT_QUOTES, 'UTF-8') ?>'>
                                                    Return <?= htmlspecialchars($bookInfo['title']) ?> To Rouen Library
                                                </h5>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal'
                                                    aria-label='Close'></button>
                                            </div>
                                            <div class='modal-body'>
                                                <p><strong><?= htmlspecialchars($user['user_name']) ?></strong> has returned this
                                                    book to the library today?
                                                </p>
                                            </div>
                                            <div class='modal-footer justify-content-between'>
                                                <button type='button' class='btn btn-secondary'
                                                    data-bs-dismiss='modal'>Close</button>
                                                <form action="return_book.php" method='post'>
                                                    <input type="hidden" name="book_id" value="<?= $bookInfo['id'] ?>">
                                                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                                    <input type="hidden" name="borrowed_date"
                                                        value="<?= $user['date_borrowed'] ?>">
                                                    <input type="hidden" name="book_name" value="<?= $bookInfo['title'] ?>">
                                                    <input type="hidden" name="user_name" value="<?= $user['user_name'] ?>">
                                            <!-- Hidden field for knowing the location to return to -->
                                            <input type="hidden" name="location" value='admin_book_details.php'>
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
                    <p>No borrowed users found <?= isset($search) ? "with name $search" : "" ?> for this book.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>