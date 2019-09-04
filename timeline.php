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

$success = [];
$errors = [];

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

require_once("./includes/fetch_post_liked_by_users.php");
require_once("./includes/fetch_is_post_liked_by_user.php");
require_once("./includes/fetch_comments_by_post.php");
require_once("./includes/generic_functions.php");

require_once("./includes/fetch_comment_liked_by_users.php");
require_once("./includes/fetch_is_comment_liked_by_user.php");
?>

<div id="container" class="mx-auto my-3">
    <div class="text-center font-weight-bold mb-2"><?= $_SESSION["fullname"] ?></div>

    <?php require_once("./includes/form_create_post.php"); ?>

    <?php if (empty($posts)) { ?>
        <div class="card p-3 my-2">
            It looks like there are no posts available to view at this moment.
        </div>
    <?php } else {
        foreach ($posts as $post) {
            echo load_post($db, $post, $profile_id);
        }
    } ?>
</div>

<?php include("./includes/modal_liked_users.php"); ?>
<?php include("./includes/modal_share_post.php"); ?>

<?php include("./includes/footer.php"); ?>
<script src="assets/js/postActions.js"></script>