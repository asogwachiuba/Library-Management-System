<?php
session_start();
$title = "Registration";
include 'header.php';
include 'menu.php';
include 'message_popup.php';
?>
<div class="container"
    style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 15px; padding: 30px; width: 90%; max-width: 600px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);">
    <div class="container" style="text-align: center;">
        <h1
            style="margin-bottom: 20px; font-size: 2rem; font-weight: bold; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">
            Registration</h1>
        <form method="POST" action="handle_registration.php" enctype="multipart/form-data">
            <div class="container" style="margin-top: 20px;">
                <div class="row my-3" style="display: flex; gap: 15px; justify-content: space-between;">
                    <div class="col-md-6" style="flex: 1;">
                        <label for="name" class="welcome purple-text"
                            style="font-weight: bold; display: block; margin-bottom: 5px;">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Your Name..."
                            required
                            style="padding: 10px; border: none; border-radius: 5px; width: 100%; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    </div>
                    <div class="col-md-6" style="flex: 1;">
                        <label for="photo" class="welcome purple-text"
                            style="font-weight: bold; display: block; margin-bottom: 5px;">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" required
                            style="padding: 10px; border: none; border-radius: 5px; width: 100%; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    </div>
                </div>
                <div class="row my-3" style="display: flex; gap: 15px; justify-content: space-between;">
                    <div class="col-md-6" style="flex: 1;">
                        <label for="email" class="welcome purple-text"
                            style="font-weight: bold; display: block; margin-bottom: 5px;">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Enter Your Email..." required
                            style="padding: 10px; border: none; border-radius: 5px; width: 100%; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    </div>
                    <div class="col-md-6" style="flex: 1;">
                        <label for="password" class="welcome purple-text"
                            style="font-weight: bold; display: block; margin-bottom: 5px;">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Votre mot de passe..." required
                            style="padding: 10px; border: none; border-radius: 5px; width: 100%; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    </div>
                </div>
                <div class="row my-3" style="text-align: center; margin-top: 20px;">
                    <button class="btn btn-outline-primary" type="submit"
                        style="background: #5a189a; color: white; border: none; border-radius: 5px; padding: 10px 20px; font-size: 1rem; font-weight: bold; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); transition: transform 0.2s;">
                        Register
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
include 'footer.php';
?>