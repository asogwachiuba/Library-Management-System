<?php
// Include the database connection
require_once("conn.php");

// Fetch the latest 3 books from the database
$query = "SELECT title, photo, description FROM books ORDER BY id ASC LIMIT 3";
$stmt = $pdo->prepare($query);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="max-width: 70%; margin: 0 auto; height: 100vh; padding-top: 20px;">
    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            // Check if books are fetched
            if ($books && count($books) > 0):
                $active = true; // To set the first item as active
                foreach ($books as $book):
                    ?>
                    <div class="carousel-item <?= $active ? 'active' : '' ?> position-relative">
                        <img src="images/<?= htmlspecialchars($book['photo'], ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?>"
                            style="width: 100%; height: 100%; object-fit: contain; border-radius: 10px;">
                        <div class="overlay">
                            <h2 class="image-title"><?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                            <p class="image-description"><?= htmlspecialchars($book['description'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </div>
                    <?php
                    $active = false; // Set subsequent items as non-active
                endforeach;
            else:
                ?>
                <div class="carousel-item active position-relative">
                    <img src="images/default.jpg" class="d-block artistic-image" alt="No books available">
                    <div class="overlay">
                        <h2 class="image-title">No Books Available</h2>
                        <p class="image-description">Please add books to the library to display them here.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>