<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$title = $_SESSION["user_name"];
if (! isset($_SESSION['user_id'])) {
    header("Location: index.php?from=none");
    exit();
}

$success = array();
$errors = array();
$posts = array();

require_once("./utilities/connect_to_db.php");

// Check if the provided id is valid, and fetch user's name
if (! isset($_GET["id"])) {
    $profile_id = (int) $_SESSION["user_id"];
    $profile_name = $_SESSION["user_name"];
} else {
    $id = (int) $_GET["id"];
    $query = "SELECT CONCAT(fname, ' ', lname) AS user_name from users where user_id = ?";
    $stmt = $db->stmt_init();
    $stmt->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($name);
    $stmt->fetch();

    if (isset($name)) {
        $profile_id = $id;
        $profile_name = $name;
        $stmt->close();
    } else {
        header("Location: page_not_found.php");
        exit();
    }
}

$is_own_profile = $profile_id == $_SESSION["user_id"];

require_once("./classes/post.class.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("./utilities/process_create_post.php");
}

include("./includes/header.php");
include("./includes/nav.php");
include("./includes/posts_by_user.php");
?>

<div class="w-50 my-3 vertical-center">
    <div class="text-center font-weight-bold"><?= $profile_name ?></div>
    <?php if ($is_own_profile) { ?>
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
    <?php } ?>

    <?php if (empty($posts)) { ?>
        <div class="card p-3 my-2">
            It looks like there are no posts available to view at this moment.
        </div>
    <?php } else {
            foreach ($posts as $post) {
        ?>
            <div class="card p-3 my-2">
                <div class="card-title font-weight-bold">
                    <a href=<?php "profile.php?id={$post->post_owner_id}" ?> ><?= $post->post_owner_name ?></a>
                </div>
                <div class="card-subtitle"><?= $post->posted_on ?></div>
                <div class="card-text my-2"><?= nl2br($post->post_content) ?></div>

                <hr>
                <div class="row">
                    <div class="col-sm-4 text-center">
                        <a href="#" class="btn btn-sm btn-primary">Like <i class="fas fa-thumbs-up"></i></a>
                    </div>
                    <div class="col-sm-4 text-center">
                        <a href="#" class="btn btn-sm btn-primary">Comment <i class="fas fa-comments"></i></a>
                    </div>
                    <div class="col-sm-4 text-center">
                        <a href="#" class="btn btn-sm btn-primary">Share <i class="fas fa-share"></i></a>
                    </div>
                </div>
            </div>
        <?php }
     } ?>
</div>
<?php include("./includes/footer.php"); ?>