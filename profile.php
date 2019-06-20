<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    $profile_name = $_SESSION["fullname"];
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
        $own_id = $_SESSION["user_id"];

        $stmt->close();
    } else {
        header("Location: page_not_found.php");
        exit();
    }
}

$title = $profile_name;
$is_own_profile = $profile_id == $_SESSION["user_id"];

require_once("./classes/post.class.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("./utilities/process_create_post.php");
}

include("./includes/header.php");
include("./includes/nav.php");
include("./includes/fetch_posts_by_user.php");
if (! $is_own_profile) {
    include("./includes/fetch_mutual_friend_count.php");
}
?>

<div class="w-50 my-3 vertical-center">
    <?php if (! $is_own_profile) { ?>
        <div class="text-center font-weight-bold mb-2"><?= $profile_name ?></div>
    <?php } else { ?>
        <div class="text-center font-weight-bold mb-3"><?= $profile_name ?></div>
    <?php } ?>

    <?php if (! $is_own_profile) { ?>
        <div class="text-center mb-3">
            <?php include_once("./includes/friendship_status.php"); ?>

            <span>
                <a href="message.php?id=<?= $profile_id ?>" class="btn btn-sm btn-info">Message <i class="fas fa-envelope"></i></a>
            </span>
        </div>
        <?php if (! $is_own_profile) { ?>
            <div class="text-center mb-3">
                <a href="mutual_friends.php?id=<?= $profile_id ?>">
                    <span class="text-secondary">
                        <?php echo count($mutual_friend_list) === 0 ? "No" : count($mutual_friend_list) ?> mutual friend
                        <?php echo count($mutual_friend_list) > 1 ? "s" : "" ?>
                    </span>
                </a>
            </div>
        <?php } ?>
    <?php } ?>

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
        include("./includes/show_posts.php");
    } ?>
</div>
<?php include("./includes/footer.php"); ?>