<?php
session_start();//for messages
$title = "Welcome";
include 'header.php';
include 'menu.php';
include 'message_popup.php';
?>

<?php
if (isset($_SESSION['message'])) {

    ?>
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
        <?= $_SESSION['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- remove the received a message -->
    <?php
    unset($_SESSION['message']);

}
?>
<div class="background-container">

    <!-- if a message was sent by another page Display it-->

    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class='welcome'>Welcome To Rouen Library</h1>
            <p> "Your Gateway to Knowledge and Imagination",
                Discover thousands of books that inspire, educate, and
                entertain. From timeless classics to the latest bestsellers, we have something for every reader</p>
        </div>

        <div class="col-md-6">
            <?php include 'carousel.php'; ?>
        </div>
    </div>
</div>
<?php
include 'footer.php';
?>