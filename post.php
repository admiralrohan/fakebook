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

require_once("./utilities/connect_to_db.php");
include_once("./includes/generic_functions.php");

require_once("./classes/post.class.php");
require_once("./classes/user.class.php");
require_once("./classes/comment.class.php");

require_once("./includes/fetch_post_by_id.php");
require_once("./includes/fetch_post_liked_by_users.php");
require_once("./includes/fetch_is_post_liked_by_user.php");
require_once("./includes/fetch_comments_by_post.php");

// Check if post_id exists and if exists then if the provided id is valid
if (!isset($_GET["id"])) {
    header("Location: page_not_found.php");
    exit();
} else {
    $id = (int) $_GET["id"];
    $own_id = (int) $_SESSION["user_id"];

    $post = post_by_id($db, $id);
    $post_owner = new User($post->owner->id, $post->owner->name);
    $title = $post_owner->name . "'s Post";
}
include("./includes/header.php");
include("./includes/nav.php");
?>

<div id="container" class="mx-auto my-3">
    <?= load_post($db, $post, $own_id) ?>
</div>

<?php //include("./includes/modal_liked_users.php");
?>
<?php //include("./includes/modal_shared_posts.php");
?>

<?php include("./includes/footer.php"); ?>
<script src="assets/js/post-actions.js"></script>