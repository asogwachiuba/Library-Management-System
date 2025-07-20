<div class="col-md-6">
    <div class="card admin-dashboard-card">
        <div class="card-header text-center text-uppercase" style="color: white">User Management</div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <?php
                require_once("conn.php");
                $req = "
                            SELECT *
                            FROM users
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
                                style="color: white"><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></span>
                            <div class="d-flex">
                                <form action="view_user.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button class="btn btn-sm btn-outline-light action-btn me-2">View Info</button>
                                </form>
                                <button class="btn btn-sm btn-danger action-btn me-2" data-bs-toggle="modal"
                                    data-bs-target="#modal-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    Delete</button>
                                <?php if ($row['role'] == 0): ?>
                                    <span class="text-success">An admin</span>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-success action-btn" data-bs-toggle="modal"
                                        data-bs-target="#modal-upgrade-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>">Upgrade
                                        to Admin</button>
                                <?php endif; ?>

                            </div>
                            <div class='modal fade' id='modal-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>'
                                tabindex='-1'
                                aria-labelledby='modalLabel-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>'
                                aria-hidden='true'>
                                <div class='modal-dialog modal-dialog-centered modal-lg'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='modalLabel-$bookId'>Confirm Delete</h5>
                                            <button type='button' class='btn-close' data-bs-dismiss='modal'
                                                aria-label='Close'></button>
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
                                            <button type='button' class='btn btn-secondary'
                                                data-bs-dismiss='modal'>Close</button>
                                            <?php if ($row['id'] != $_SESSION['profile']['id']): ?>
                                                <form action="delete_user.php" method='post'>
                                                    <input type="hidden" name="id" value=<?= $row['id'] ?>>
                                                    <button type='submit' class='btn btn-primary'>Delete</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='modal fade' id='modal-upgrade-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>'
                                tabindex='-1'
                                aria-labelledby='modalLabel-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>'
                                aria-hidden='true'>
                                <div class='modal-dialog modal-dialog-centered modal-lg'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='modalLabel-$bookId'>Upgrade To Admin</h5>
                                            <button type='button' class='btn-close' data-bs-dismiss='modal'
                                                aria-label='Close'></button>
                                        </div>
                                        <div class='modal-body'>
                                            <p>Are you sure, you want to
                                                change <strong><?= htmlspecialchars($row['name']) ?></strong>
                                                role from a user to admin. </p>
                                        </div>
                                        <div class='modal-footer justify-content-between'>
                                            <button type='button' class='btn btn-secondary'
                                                data-bs-dismiss='modal'>Close</button>
                                            <form action="upgrade_user.php" method='post'>
                                                <input type="hidden" name="id" value=<?= $row['id'] ?>>
                                                <input type="hidden" name="name" value=<?= $row['name'] ?>>
                                                <button type='submit' class='btn btn-primary'>Upgrade</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php
                    }
                } else { ?>
                    <!-- Display no user currently placeholder  -->
                    <div class="d-flex flex-column align-items-center justify-content-center mt-5">
                        <div class="card shadow-sm p-4 border-0"
                            style="max-width: 400px; background-color: #f9f9f9; border-radius: 12px;">
                            <img src="images/no-user.jpg" alt="No Books Returned" class="img-fluid mb-3"
                                style="border-radius: 8px; max-width: 100%;">
                            <h5>No user is registered</h5>
                        </div>
                    </div>

                    <?php
                }
                ?>

            </ul>
            <div class="d-flex justify-content-between mt-3">
                <div></div>
                <a href="listusers.php" class="btn btn-outline-light">See All Users</a>
            </div>
        </div>
    </div>
</div>