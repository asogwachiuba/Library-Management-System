<?php
session_start();
$title = "Users List";
include 'header.php';
include 'menu.php';
include 'role_admin.php';
?>
<div class="container my-5">
  <h1 class="text-center mb-4">Users List</h1>

  <table class="table table-hover table-bordered align-middle">
    <thead class="table-dark text-center">
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Role</th>
        <th scope="col">Photo</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Connection
      require_once("conn.php");
      $req = "SELECT * FROM users";
      $ps = $pdo->prepare($req);
      $ps->execute();
      while ($row = $ps->fetch()) {
        $role = $row['role'] == 0
          ? '<span class="badge bg-success">Admin</span>'
          : '<span class="badge bg-secondary">User</span>';
        ?>
        <tr>
          <th scope="row" class="text-center"><?= $row['id'] ?></th>
          <td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?></td>
          <td class="text-center"><?= $role ?></td>
          <td class="text-center">
            <img src="images/<?= htmlspecialchars($row['photo'], ENT_QUOTES, 'UTF-8') ?>" alt="User Photo"
              class="rounded-circle" width="50px" height="50px">
          </td>
          <td class="text-center">
            <!-- View Button -->
            <form action="view_user.php" method="POST" style="display:inline;">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <button type="submit" class="btn btn-sm btn-info me-2">View</button>
            </form>

            <!-- Delete Button -->
            <button class="btn btn-sm btn-danger me-2" data-bs-toggle="modal"
              data-bs-target="#modal-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>">Delete</button>

            <!-- Upgrade to Admin Button -->
            <?php if ($row['role'] != 0) { ?>
              <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                data-bs-target="#modal-upgrade-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>">Upgrade to
                Admin</button>
            <?php } ?>
          </td>
          <div class='modal fade' id='modal-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>' tabindex='-1'
            aria-labelledby='modalLabel-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>' aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered modal-lg'>
              <div class='modal-content'>
                <div class='modal-header'>
                  <h5 class='modal-title' id='modalLabel-$bookId'>Confirm Delete</h5>
                  <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                  <?php if ($row['id'] == $_SESSION['profile']['id']): ?>
                    <p>You can not delete your own account.
                    </p>
                  <?php else: ?>
                    <p>Are you sure, you want to
                      delete <strong><?= htmlspecialchars($row['name']) ?></strong>
                      account. All information regarding
                      <?= htmlspecialchars($row['name']) ?> will be lost.
                    </p>
                  <?php endif; ?>
                </div>
                <div class='modal-footer justify-content-between'>
                  <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                  <?php if ($row['id'] != $_SESSION['profile']['id']): ?>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </tr>
        <div class='modal fade' id='modal-upgrade-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>' tabindex='-1'
          aria-labelledby='modalLabel-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>' aria-hidden='true'>
          <div class='modal-dialog modal-dialog-centered modal-lg'>
            <div class='modal-content'>
              <div class='modal-header'>
                <h5 class='modal-title' id='modalLabel-$bookId'>Upgrade To Admin</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
              </div>
              <div class='modal-body'>
                <p>Are you sure, you want to
                  change <strong><?= htmlspecialchars($row['name']) ?></strong>
                  role from a user to admin. </p>
              </div>
              <div class='modal-footer justify-content-between'>
                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                <form action="upgrade_user.php" method='post'>
                  <input type="hidden" name="id" value=<?= $row['id'] ?>>
                  <button type='submit' class='btn btn-primary'>Upgrade</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
      ?>
    </tbody>
  </table>
</div>

<?php
include 'footer.php';
?>