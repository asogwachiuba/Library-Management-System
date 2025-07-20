<nav class="navbar navbar-expand-md bg-dark border-bottom border-body" data-bs-theme="dark">
  <div class="container-fluid">
    <!-- Dynamic Home Link -->
    <a class="navbar-brand"
      href="<?php echo !isset($_SESSION['profile']) ? 'index.php' : ($_SESSION['profile']['role'] == 1 ? 'user_page.php' : 'admin_page.php'); ?>">Rouen
      Library</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
      aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav me-auto mb-lg-0">
        <?php if (!isset($_SESSION['profile'])): ?>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Welcome</a>
          </li>
        <?php endif; ?>
        <?php if (isset($_SESSION['profile']) && $_SESSION['profile']['role'] === 0): ?>
          <li class="nav-item">
            <a class="nav-link" href="listusers.php">List Users</a>
          </li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav mb-lg-0">
        <?php if (!isset($_SESSION['profile'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="registration.php">Registration</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="connection.php">Connection</a>
          </li>
        <?php else: ?>
          <!-- Profile Picture and name -->
          <li class="nav-item me-3 d-flex align-items-center">
            <h4 class="mb-0 me-2 text-white"><?= $_SESSION['profile']['name'] ?></h4>
            <a class="nav-link p-0" href="edit.php?id=<?= $_SESSION['profile']['id'] ?>">
              <img
                src="<?= isset($_SESSION['profile']['photo']) ? 'images/' . $_SESSION['profile']['photo'] : 'images/default_profile.jpg'; ?>"
                alt="Profile Picture" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
            </a>
          </li>

          <!-- Trigger the Logout Modal -->
          <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
          </li>
        <?php endif; ?>
        <?php if (isset($_SESSION['profile']) && $_SESSION['profile']['role'] === 1): ?>
          <li class="nav-item">
            <a class="nav-link" href="book_history.php">
              <i class="bi bi-book-half"></i>
            </a>
          </li>
        <?php endif; ?>
      </ul>

    </div>
  </div>
</nav>


<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to logout?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="logout.php" class="btn btn-danger">Logout</a>
      </div>
    </div>
  </div>
</div>