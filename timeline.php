<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 'On');
ini_set('html_errors', 1);
error_reporting(-1);


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?from=none");
    exit();
}
$title = $_SESSION["fname"] . "'s Timeline";
$profile_id = (int) $_SESSION["user_id"];

$success = array();
$errors = array();

require_once("./utilities/connect_to_db.php");
require_once("./classes/post.class.php");
require_once("./classes/user.class.php");
require_once("./classes/comment.class.php");

include("./includes/header.php");
include("./includes/nav.php");

include("./includes/fetch_timeline_posts.php");
$posts = timeline_posts($db, $profile_id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("./utilities/process_create_post.php");
}
?>

<div class="w-50 my-3 vertical-center">
    <div class="text-center font-weight-bold mb-2"><?= $_SESSION["fullname"] ?></div>

    <div class="card p-3">
        <form method="POST" action="timeline.php">
            <div class="form-group">
                <label for="post_content" class="font-weight-bold">Create Post</label>
                <?php include("./includes/show_success.php"); ?>
                <?php include("./includes/show_errors.php"); ?>

                <textarea class="form-control" id="post_content" rows="5" name="post_content" placeholder="Write something here..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-sm mb-2">Share Post</button>
        </form>
    </div>

    <?php if (empty($posts)) { ?>
        <div class="card p-3 my-2">
            It looks like there are no posts available to view at this moment.
        </div>
    <?php } else {
        include("./includes/show_posts.php");
    } ?>
</div>
<?php include("./includes/footer.php"); ?>
<script src="assets/js/post-actions.js"></script>