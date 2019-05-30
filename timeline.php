<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$title = "Timeline";
if (! isset($_SESSION['user_id'])) {
    header("Location: index.php?from=none");
    exit();
}

$success = array();
$errors = array();
$posts = array();

require_once("./utilities/connect_to_db.php");
require_once("./classes/post.class.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("./utilities/process_create_post.php");
}

include("./includes/header.php");
include("./includes/nav.php");
?>

<div class="w-50 my-3 vertical-center">
    <div class="card p-3">
        <form method="POST" action="timeline.php">
            <div class="form-group">
                <label for="post_content" class="font-weight-bold">Create Post</label>
                <?php include("./includes/show_success.php"); ?>
                <?php include("./includes/show_errors.php"); ?>
                <textarea class="form-control" id="post_content" rows="5" name="post_content" placeholder="Write something here..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary mb-2">Share Post</button>
        </form>
    </div>

    <?php if (empty($posts)) { ?>
        <div class="card p-3 my-2">
            It looks like there are no posts available to view at this moment.
        </div>
    <?php } else {
            foreach ($posts as $post) {
        ?>
            <div class="card p-3 my-2">
                <div class="card-title"><?= $post->owner ?></div>
                <div class="card-subtitle"><?= $post->posted_on ?></div>
                <div class="card-text"><?= $post->content ?></div>
            </div>
        <?php }
     } ?>
</div>
<?php include("./includes/footer.php"); ?>