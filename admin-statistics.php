<?php
require_once("conn.php");
try {
    $totalUsersQuery = "SELECT COUNT(*) AS total_users FROM users";
    $totalUsersStmt = $pdo->prepare($totalUsersQuery);
    $totalUsersStmt->execute();
    $totalUsers = $totalUsersStmt->fetch()['total_users'];
    // Fetch total books borrowed
    $booksBorrowedQuery = "SELECT COUNT(*) AS total_borrowed FROM books_borrowed";
    $booksBorrowedStmt = $pdo->prepare($booksBorrowedQuery);
    $booksBorrowedStmt->execute();
    $totalBooksBorrowed = $booksBorrowedStmt->fetch()['total_borrowed'];
    // Fetch total books returned
    $booksReturnedQuery = "SELECT COUNT(*) AS total_returned FROM books_borrowed WHERE is_returned = 1";
    $booksReturnedStmt = $pdo->prepare($booksReturnedQuery);
    $booksReturnedStmt->execute();
    $totalBooksReturned = $booksReturnedStmt->fetch()['total_returned'];
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>
<div class="row mb-5">
    <div class="col-md-4">
        <div class="card admin-dashboard-card stat-card">
            <div class="card-body text-center">
                <h3 style="color: white"><?= $totalBooksBorrowed ?></h3>
                <p class="welcome" style="color: white">Books Borrowed</p>
                <div class="icon mt-2">
                    <i class="bi bi-book-fill" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card admin-dashboard-card stat-card">
            <div class="card-body text-center">
                <h3 style="color: white"><?= $totalUsers ?></h3>
                <p class="welcome" style="color: white">Total Users</p>
                <div class="icon mt-2">
                    <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card admin-dashboard-card stat-card">
            <div class="card-body text-center">
                <h3 style="color: white"><?= $totalBooksReturned ?></h3>
                <p class="welcome" style="color: white">Books Returned</p>
                <div class="icon mt-2">
                    <i class="bi bi-arrow-return-left" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>