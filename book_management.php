<div class="col-md-6">
    <div class="card admin-dashboard-card">
        <div class="card-header text-center text-uppercase" style="color: white">Books</div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <?php
                require_once("conn.php");
                $req = "
                            SELECT *
                            FROM books
                            LIMIT 5;
                            ";
                $ps = $pdo->prepare($req);
                $ps->execute();
                // Check if any rows were returned
                if ($ps->rowCount() > 0) {
                    // Loop through returned users
                    while ($row = $ps->fetch()) { ?>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center">
                            <span class="welcome"
                                style="color: white"><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></span>
                            <div class="d-flex">
                                <button class="btn btn-sm btn-danger action-btn me-2" data-bs-toggle="modal"
                                    data-bs-target="#book-modal-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>">Delete</button>
                                <button class="btn btn-sm btn-success action-btn me-2" data-bs-toggle="modal"
                                    data-bs-target="#editModal-<?= $row['id'] ?>">Edit</button>
                                <a href="admin_book_details.php?id=<?= $row['id'] ?>"
                                    class="btn btn-sm btn-outline-light action-btn">View Details</a>
                            </div>

                            <!-- Delete modal -->
                            <div class='modal fade' id='book-modal-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>'
                                tabindex='-1'
                                aria-labelledby='modalLabel-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>'
                                aria-hidden='true'>
                                <div class='modal-dialog modal-dialog-centered modal-lg'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title'
                                                id='modalLabel-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>'>Confirm
                                                Delete</h5>
                                            <button type='button' class='btn-close' data-bs-dismiss='modal'
                                                aria-label='Close'></button>
                                        </div>
                                        <div class='modal-body'>
                                            <p>Are you sure, you want to
                                                delete <strong><?= htmlspecialchars($row['title']) ?></strong>
                                                account. All information regarding
                                                <?= htmlspecialchars($row['title']) ?> will be lost.
                                            </p>
                                        </div>
                                        <div class='modal-footer justify-content-between'>
                                            <button type='button' class='btn btn-secondary'
                                                data-bs-dismiss='modal'>Close</button>
                                            <form action="delete_book.php" method='post'>
                                                <input type="hidden" name="id" value=<?= $row['id'] ?>>
                                                <input type="hidden" name="name" value=<?= $row['title'] ?>>
                                                <button type='submit' class='btn btn-primary'>Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 
                                Edit Modal 
                            -->
                            <div class="modal fade" id="editModal-<?= $row['id'] ?>" tabindex="-1"
                                aria-labelledby="editModalLabel-<?= $row['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel-<?= $row['id'] ?>">Edit
                                                <?= $row['title'] ?> Details
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!--Form fields -->
                                            <form action="edit_book.php" method="POST" enctype="multipart/form-data">
                                                <!-- Hidden field for Book ID -->
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <!-- Book Title -->
                                                <div class="mb-3">
                                                    <label for="bookTitle-<?= $row['id'] ?>" class="form-label"
                                                        style="color: #212529;">
                                                        <strong>Book Title</strong>
                                                    </label>
                                                    <input type="text" class="form-control" id="bookTitle-<?= $row['id'] ?>"
                                                        name="title"
                                                        value="<?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?>">
                                                </div>
                                                <!-- Book Author -->
                                                <div class="mb-3">
                                                    <label for="bookAuthor-<?= $row['id'] ?>" class="form-label"
                                                        style="color: #212529;">
                                                        <strong>Book Author</strong>
                                                    </label>
                                                    <input type="text" class="form-control" id="bookAuthor-<?= $row['id'] ?>"
                                                        name="author"
                                                        value="<?= htmlspecialchars($row['author'], ENT_QUOTES, 'UTF-8') ?>">
                                                </div>
                                                <!-- Book Photo -->
                                                <div class="mb-3 d-flex align-items-center">
                                                    <div>
                                                        <label for="bookPhoto-<?= $row['id'] ?>" class="form-label"
                                                            style="color: #212529;">
                                                            <strong>Book Photo</strong>
                                                        </label>
                                                        <!-- File input for new photo -->
                                                        <input type="file" class="form-control" id="bookPhoto-<?= $row['id'] ?>"
                                                            name="new_photo">

                                                        <!-- Hidden input to hold the current photo -->
                                                        <input type="hidden" name="current_photo"
                                                            value="<?= htmlspecialchars($row['photo'], ENT_QUOTES, 'UTF-8') ?>">

                                                        <small class="text-muted">Current Photo:
                                                            <?= htmlspecialchars($row['photo'], ENT_QUOTES, 'UTF-8') ?></small>
                                                    </div>
                                                    <div class="ms-3">
                                                        <!-- Display Current Photo -->
                                                        <img src="images/<?= htmlspecialchars($row['photo'], ENT_QUOTES, 'UTF-8') ?>"
                                                            alt="Book Photo" class="img-thumbnail"
                                                            style="max-width: 150px; max-height: 150px;">
                                                        <small class="d-block text-muted mt-1">Current Photo</small>
                                                    </div>
                                                </div>
                                                <!-- Available Copies -->
                                                <div class="mb-3">
                                                    <label for="availableCopies-<?= $row['id'] ?>" class="form-label"
                                                        style="color: #212529;">
                                                        <strong>Available Copies</strong>
                                                    </label>
                                                    <input type="number" class="form-control"
                                                        id="availableCopies-<?= $row['id'] ?>" name="available_copies"
                                                        value="<?= htmlspecialchars($row['available_copies'], ENT_QUOTES, 'UTF-8') ?>">
                                                </div>
                                                <!-- Description -->
                                                <div class="mb-3">
                                                    <label for="bookDescription-<?= $row['id'] ?>" class="form-label"
                                                        style="color: #212529;">
                                                        <strong>Description</strong>
                                                    </label>
                                                    <textarea class="form-control" id="bookDescription-<?= $row['id'] ?>"
                                                        name="description"
                                                        value="<?= htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') ?>"
                                                        rows="4"><?= htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
                                                </div>

                                                <!-- Submit Button -->
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </li>
                        <?php
                    }
                } else { ?>
                    <!-- Display no book currently placeholder  -->
                    <div class="d-flex flex-column align-items-center justify-content-center mt-5">
                        <div class="card shadow-sm p-4 border-0"
                            style="max-width: 400px; background-color: #f9f9f9; border-radius: 12px;">
                            <img src="images/no-borrow2.jpeg" alt="No Books Returned" class="img-fluid mb-3"
                                style="border-radius: 8px; max-width: 100%;">
                        </div>
                    </div>
                    <?php
                }
                ?>
            </ul>
            <div class="d-flex justify-content-between mt-3">
                <a href="admin_all_books.php" class="btn btn-outline-light">See All Books</a>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addNewBookModal">Add
                    New Book</button>
            </div>
            <!-- Add New Book Modal -->
            <div class="modal fade" id="addNewBookModal" tabindex="-1" aria-labelledby="addNewBookLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addNewBookLabel">Add New Book</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="add_new_book.php" method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <!-- Book Title -->
                                <div class="mb-3">
                                    <label for="bookTitle" class="form-label" style="color: #212529;"><strong>Book
                                            Title</strong></label>
                                    <input type="text" class="form-control" id="bookTitle" name="title" required>
                                </div>
                                <!-- Book Author -->
                                <div class="mb-3">
                                    <label for="bookAuthor" class="form-label"
                                        style="color: #212529;"><strong>Author</strong></label>
                                    <input type="text" class="form-control" id="bookAuthor" name="author" required>
                                </div>
                                <!-- Book Photo -->
                                <div class="mb-3">
                                    <label for="bookPhoto" class="form-label" style="color: #212529;"><strong>Book
                                            Photo</strong></label>
                                    <input type="file" class="form-control" id="bookPhoto" name="photo" required>
                                    <small class="text-muted">Upload a valid image file (jpg, png, etc.).</small>
                                </div>
                                <!-- Available Copies -->
                                <div class="mb-3">
                                    <label for="availableCopies" class="form-label"
                                        style="color: #212529;"><strong>Available Copies</strong></label>
                                    <input type="number" class="form-control" id="availableCopies"
                                        name="available_copies" min="0" required>
                                </div>
                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label"
                                        style="color: #212529;"><strong>Description</strong></label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add Book</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>