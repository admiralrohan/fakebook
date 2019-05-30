<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$title = "Fakebook - Login";
if (isset($_SESSION['user_id'])) {
    header("Location: timeline.php");
    exit();
}

$success = array();
$errors = array();

require_once("./utilities/connect_to_db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("./utilities/process_login_page.php");
}
// print_r($_GET);
// print_r($_SESSION);
if (isset($_GET["from"])) {
    if ($_GET["from"] === "none") {
        $errors[] = "Login first to access restricted pages.";
    } else if ($_GET["from"] === "logout" && $_GET["success"] === "false") {
        $errors[] = "Your session is already timed out.";
    } else if ($_GET["from"] === "register") {
        $success[] = "Successfully registered. You can login now.";
    } else if ($_GET["from"] === "logout" && $_GET["success"] === "true") {
        $success[] = "Successfully logged out.";
    }
}
?>

<?php include("./includes/header.php"); ?>
<div class="card w-50 p-4 my-5 vertical-center">
    <div class="card-title text-center font-weight-bold logo">Fakebook</div>
    <div class="card-subtitle text-center h6">Login into your account</div>
    <div class="card-body">
        <?php include("./includes/show_success.php"); ?>
        <?php include("./includes/show_errors.php"); ?>

        <form method="POST" action="index.php">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control form-control-sm" id="email" placeholder="Enter email" name="email" value="<?= $_POST["email"] ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control form-control-sm" id="password" placeholder="Password" name="password">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
        </form>
        <br>
        <div>
            Don't have a account yet? <a href="register.php">Register Here</a>
        </div>
    </div>
</div>
<?php include("./includes/footer.php"); ?>