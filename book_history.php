<?php
session_start();
$title = "Book History";
if (!(isset($_SESSION['profile']))) {
    
    header('Location: index.php');
    exit;
}
include 'header.php';
include 'menu.php';
include 'message_popup.php';
?>

<div class="container">
    <div class="row my-3">
        <!-- Borrowed Section -->
        <div class="col-md-6">
            <h3 class="mb-4 text-secondary">Borrowed</h3>
            <?php
            require_once("conn.php");
            $req = "
            SELECT b.title, b.author, b.description, b.photo, bb.date_borrowed
            FROM books_borrowed bb
            JOIN books b ON bb.book_id = b.id
            WHERE bb.user_id = ? AND bb.is_returned = 0
        ";
            $user_id = $_SESSION['profile']['id'];
            $param = array($user_id);
            $ps = $pdo->prepare($req);
            $ps->execute($param);
            // Check if any rows were returned
            if ($ps->rowCount() > 0) {
                // Loop through returned books
                while ($row = $ps->fetch()) { ?>

                    <div class="card shadow-sm mb-4" style="border-radius: 12px; overflow: hidden; border: 1px solid #ddd;">
                        <div class="row g-0">
                            <!-- Book Image Section -->
                            <div class="col-md-4">
                                <img src="images/<?= htmlspecialchars($row['photo']); ?>"
                                    alt="<?= htmlspecialchars($row['title']); ?>" class="img-fluid h-100"
                                    style="object-fit: cover;">
                            </div>
                            <!-- Book Info Section -->
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title purple-text welcome mb-3"><?= htmlspecialchars($row['title']); ?></h5>
                                    <p class="card-text"><strong>Author:</strong> <?= htmlspecialchars($row['author']); ?></p>
                                    <p class="card-text text-muted" style="font-size: 0.9rem;"><strong>Description:</strong>
                                        <?= htmlspecialchars($row['description']); ?></p>
                                    <p class="card-text"><span class="badge bg-dark">Date Borrowed:</span>
                                        <?= htmlspecialchars($row['date_borrowed']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                }
            } else { ?>
                <!-- Display No borrowed book empty place holder -->
                <div class="d-flex flex-column align-items-center justify-content-center mt-5">
                    <div class="card shadow-sm p-4 border-0"
                        style="max-width: 400px; background-color: #f9f9f9; border-radius: 12px;">
                        <img src="images/no-borrow2.jpeg" alt="No Books Returned" class="img-fluid mb-3"
                            style="border-radius: 8px; max-width: 100%;">
                        <h5 class="text-secondary mb-2">No Books Borrowed</h5>
                        <p class="text-muted">You have not returned any books yet. Start borrowing to see your activity
                            here.</p>
                    </div>
                </div>

                <?php
            }
            ?>
        </div>

        <!-- Previously Borrowed Section -->
        <div class="col-md-6">
            <h3 class="mb-4 text-secondary">Previously Borrowed</h3>
            <?php
            require_once("conn.php");
            $req = "
            SELECT b.title, b.author, b.description, b.photo, bb.date_borrowed, bb.date_returned
            FROM books_borrowed bb
            JOIN books b ON bb.book_id = b.id
            WHERE bb.user_id = ? AND bb.is_returned = 1
        ";
            $user_id = $_SESSION['profile']['id'];
            $param = array($user_id);
            $ps = $pdo->prepare($req);
            $ps->execute($param);
            // Check if any rows were returned
            if ($ps->rowCount() > 0) {
                // Loop through returned books
                while ($row = $ps->fetch()) { ?>
                    <div class="card shadow-sm mb-4" style="border-radius: 12px; overflow: hidden; border: 1px solid #ddd;">
                        <div class="row g-0">
                            <!-- Book Image Section -->
                            <div class="col-md-4">
                                <img src="images/<?= htmlspecialchars($row['photo']); ?>"
                                    alt="<?= htmlspecialchars($row['title']); ?>" class="img-fluid h-100"
                                    style="object-fit: cover;">
                            </div>
                            <!-- Book Info Section -->
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title purple-text welcome mb-3"><?= htmlspecialchars($row['title']); ?></h5>
                                    <p class="card-text"><strong>Author:</strong> <?= htmlspecialchars($row['author']); ?></p>
                                    <p class="card-text text-muted" style="font-size: 0.9rem;"><strong>Description:</strong>
                                        <?= htmlspecialchars($row['description']); ?></p>
                                    <p class="card-text"><span class="badge bg-dark">Date Borrowed:</span>
                                        <?= htmlspecialchars($row['date_borrowed']); ?></p>
                                    <p class="card-text"><span class="badge bg-success">Date Returned:</span>
                                        <?= htmlspecialchars($row['date_returned']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else { ?>
                <!-- Display No Previously borrowed empty place holder -->
                <div class="d-flex flex-column align-items-center justify-content-center mt-5">
                    <div class="card shadow-sm p-4 border-0"
                        style="max-width: 400px; background-color: #f9f9f9; border-radius: 12px;">
                        <img src="images/no-borrow1.jpeg" alt="No Books Returned" class="img-fluid mb-3"
                            style="border-radius: 8px; max-width: 100%;">
                        <h5 class="text-secondary mb-2">No Books Returned</h5>
                        <p class="text-muted">You have not returned any books yet. Start borrowing and returning the books
                            to see your activity
                            here.</p>
                    </div>
                </div>

                <?php
            }
            ?>
        </div>
    </div>
</div>