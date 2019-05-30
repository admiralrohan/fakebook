<?php
$title = "Fakebook - Register";
require_once("./utilities/connect_to_db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("./utilities/process_register_page.php");
}
?>

<?php include("./includes/header.php"); ?>
<div class="card w-50 p-4 my-5 vertical-center">
    <div class="card-title text-center font-weight-bold logo">Fakebook</div>
    <div class="card-subtitle text-center h6">Register a new account</div>
    <div class="card-body">
        <?php include("./includes/show_errors.php"); ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="fname">First Name</label>
                <input type="text" class="form-control form-control-sm" id="fname" placeholder="Enter first name" name="fname" value="<?= $_POST["fname"] ?>">
            </div>
            <div class="form-group">
                <label for="fname">Last Name</label>
                <input type="text" class="form-control form-control-sm" id="lname" placeholder="Enter last name" name="lname" value="<?= $_POST["lname"] ?>">
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control form-control-sm" id="email" placeholder="Enter email" name="email" value="<?= $_POST["email"] ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control form-control-sm" id="password" placeholder="Password" name="password">
            </div>
            <div class="form-group">
                <label for="cpassword">Confirm Password</label>
                <input type="password" class="form-control form-control-sm" id="cpassword" placeholder="Confirm password" name="cpassword">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
        </form>
        <br>
        <div>
            Already have an account? <a href="index.php">Login Here</a>
        </div>
    </div>
</div>
<?php include("./includes/footer.php"); ?>