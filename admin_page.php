<?php
session_start();
$user = $_SESSION['profile'];
$title = "Rouen Library";
$numberOfUsers = 0;
include 'role_admin.php';
include 'header.php';
include 'menu.php';
include 'message_popup.php';
?>

<div class="background-container admin-dashboard-body">
    <div class="container my-5">
        <h1 class="text-center mb-5">Admin Dashboard</h1>

        <!-- Statistics Section -->
        <?php include 'admin-statistics.php' ?>

        <div class="row mb-5">
            <!-- Chart Section for Books Borrowed -->
            <div class="col-md-8">
                <?php include 'book_chart.php'; ?>
            </div>

            <!-- Chart Section for Books Return Rate -->
            <div class="col-md-4">
                <?php include 'book_return_rate.php'; ?>
            </div>
        </div>





        <div class="row mb-5">
            <!-- User Management Section -->
            <?php include 'user_management.php' ?>

            <!-- Books Section -->
            <?php include 'book_management.php' ?>
        </div>
    </div>
</div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>