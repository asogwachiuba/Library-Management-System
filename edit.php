<?php
session_start();
$title = "Edit Profile";
include 'header.php';

if (!(isset($_SESSION['profile']))) {
    $_SESSION['error'] = 'Only logged in user can access this page. Login with your registered account';
    header('Location: index.php');
    exit;
}
include 'menu.php';
$id = htmlentities($_GET['id']);
require_once("conn.php");
$req = "SELECT * FROM users WHERE id=?";
$ps = $pdo->prepare($req);
$params = array($id);
$ps->execute($params);
$user = $ps->fetch();



?>

<div class="profile-background">
    <div class="profile-container">
        <div class="profile-header">
            <img src="images/default_profile.jpg" alt="Profile Picture" class="profile-pic" id="profile-pic">
            <h2 class="mt-3">Welcome, <span id="username">John Doe</span></h2>
        </div>

        <form method="POST" action="update.php" enctype="multipart/form-data">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control rounded-btn" id="name" name="name"
                        value="<?= htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control rounded-btn" id="email" name="email"
                        placeholder="Enter Your Email..." value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control rounded-btn" id="password" name="password"
                        placeholder="Enter New Password...">
                    <small class="welcome" style="color: white; fon">Leave blank if you don't want
                        to change
                        your password.
                    </small>

                </div>

                <div class="col-md-6">
                    <label for="photo" class="form-label">Profile Picture</label>
                    <input type="file" class="form-control rounded-btn" id="new_photo" name="new_photo">
                </div>
            </div>

            <!-- Hidden ID field -->
            <input type="hidden" id="current_photo" name="current_photo"
                value="<?= htmlspecialchars($user['photo']); ?>">
            <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($user['id']); ?>">

            <div class="text-center">
                <button type="submit" style="
            background: linear-gradient(to right, #6a5acd, #8a2be2); /* Gradient colors */
            color: #ffffff; /* White text for contrast */
            font-weight: bold; /* Bold text */
            border: 2px solid #ffffff; /* White border for better visibility */
            border-radius: 30px; /* Rounded corners */
            padding: 10px 20px; /* Adequate padding for better size */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); /* Shadow for depth */
            cursor: pointer; /* Pointer cursor */
            transition: transform 0.2s ease, background-color 0.3s ease; /* Smooth hover effect */
        " onmouseover="this.style.transform='scale(1.1)'; " onmouseout="this.style.transform='scale(1)'; ">
                    Save Changes
                </button>
            </div>

        </form>
    </div>
</div>


<script>
    // Dynamic username and profile picture rendering (optional if you fetch via session or backend)
    document.getElementById('username').textContent = '<?= htmlspecialchars($user['name']); ?>';
    document.getElementById('profile-pic').src = 'images/<?= $user['photo'] ?? 'default_profile.jpg'; ?>';
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php
include 'footer.php';
?>