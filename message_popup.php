<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger d-flex justify-content-between align-items-center">
        <?php
        echo $_SESSION['error'];
        unset($_SESSION['error']); // Clear the error message
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (isset($_SESSION['message'])): ?>
    <div class="alert alert-success d-flex justify-content-between align-items-center">
        <?php
        echo $_SESSION['message'];
        unset($_SESSION['message']); // Clear the success message
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    </div>
<?php endif; ?>