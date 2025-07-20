<?php
session_start();
$title = "Connection";
include 'header.php';
include 'menu.php';
include 'message_popup.php';
?>

<div class="container" style="text-align: center;">
    <h1 style="margin-bottom: 20px; font-size: 2rem; font-weight: bold; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">
        Connection</h1>
    <form method="POST" action="handle_connection.php" enctype="multipart/form-data">
        <div class="container" style="margin-top: 20px; ">

            <div class="row my-3" style="margin-bottom: 15px;">
                <div class="col-md-3"></div>
                <div class="col-md-6" style="margin-bottom: 20px;">
                    <label for="email" class="welcome purple-text"
                        style="font-weight: bold; display: block; margin-bottom: 5px;">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Your Email..."
                        required
                        style="padding: 10px; border: none; border-radius: 5px; width: 100%; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                </div>
            </div>
            <div class="row my-3" style="margin-bottom: 20px;">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <label for="password" class="welcome purple-text"
                        style="font-weight: bold; display: block; margin-bottom: 5px;">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Votre mot de passe..." required
                        style="padding: 10px; border: none; border-radius: 5px; width: 100%; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                </div>
            </div>
            <div class="row my-3" style="text-align: right; margin-top: 20px;">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <a class="welcome " data-bs-toggle="modal" data-bs-target="#forgotPasswordModal" style=" color: red; border: none; border-radius: 5px; padding: 10px 20px; font-size: 1rem;
                        font-weight: bold;">
                        Forgot password?
                    </a>

                </div>

                <div class="row my-3" style="text-align: center; margin-top: 20px;">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-primary" type="submit"
                            style="background: #5a189a; color: white; border: none; border-radius: 5px; padding: 10px 20px; font-size: 1rem; font-weight: bold; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); transition: transform 0.2s;">
                            Login
                        </button>
                    </div>

                </div>
            </div>
    </form>
</div>

<!-- 
                                Edit Modal 
                            -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title welcome" id="forgotPasswordModalLabel" style="color: white">
                    Update Your Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!--Form fields -->
                <form action="update_password.php" method="POST" enctype="multipart/form-data"
                    onsubmit="return validatePasswords()">
                    <!-- User Name -->
                    <div class="mb-3" style="text-align: left;">
                        <label for="user-name" class="form-label " style="color: #212529;">
                            <strong>Name</strong>
                        </label>
                        <input type="text" class="form-control" id="user-name" name="name" required>
                    </div>
                    <!-- User Email -->
                    <div class="mb-3" style="text-align: left;">
                        <label for="user-email" class="form-label" style="color: #212529;">
                            <strong>Email</strong>
                        </label>
                        <input type="text" class="form-control" id="user-email" name="email" required>
                    </div>
                    <!-- New Password -->
                    <div class="mb-3" style="text-align: left;">
                        <label for="user-new-password" class="form-label" style="color: #212529;">
                            <strong>New Password</strong>
                        </label>
                        <input type="password" class="form-control" id="user-new-password" name="new_password" required>
                    </div>
                    <!-- Confirm Password -->
                    <div class="mb-3" style="text-align: left;">
                        <label for="user-confirm-password" class="form-label" style="color: #212529; text-align: left">
                            <strong>Confirm Password</strong>
                        </label>
                        <input type="password" class="form-control" id="user-confirm-password" name="confirm_password"
                            required>
                    </div>

                    <!-- Error Message -->
                    <div id="password-error" style="color: red; display: none;">
                        Passwords do not match. Please crosss check.
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary" style="background-color: #5a189a">Save
                        Changes</button>
                </form>

                <script>
                    function validatePasswords() {
                        const newPassword = document.getElementById("user-new-password").value;
                        const confirmPassword = document.getElementById("user-confirm-password").value;
                        const errorDiv = document.getElementById("password-error");

                        if (newPassword !== confirmPassword) {
                            // Show error message and prevent form submission
                            errorDiv.style.display = "block";
                            return false;
                        }

                        // Hide error message and allow form submission
                        errorDiv.style.display = "none";
                        return true;
                    }
                </script>

            </div>

        </div>
    </div>
</div>

<?php
include 'footer.php';
?>