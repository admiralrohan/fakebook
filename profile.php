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

$success = [];
$errors = [];

require_once("./utilities/connect_to_db.php");

// Check if the provided id is valid, and fetch user's name
if (!isset($_GET["id"])) {
    $profile_id = (int) $_SESSION["user_id"];
    $own_id = (int) $_SESSION["user_id"];
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
        $own_id = (int) $_SESSION["user_id"];

        $stmt->close();
    } else {
        header("Location: page_not_found.php");
        exit();
    }
}

$title = $profile_name;
$is_own_profile = $profile_id == $own_id;

require_once("./classes/post.class.php");
require_once("./classes/user.class.php");
require_once("./classes/comment.class.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("./utilities/process_create_post.php");
}

require_once("./includes/header.php");
require_once("./includes/nav.php");
include_once("./includes/generic_functions.php");

include_once("./includes/fetch_friendship_status_code.php");
include_once("./includes/fetch_friendship_button.php");

require_once("./includes/fetch_posts_by_user.php");
$posts = posts_by_user($db, $profile_id);

if (!$is_own_profile) {
    include("./includes/fetch_mutual_friend_count.php");
    $mutual_friends = mutual_friends($db, $own_id, $profile_id);
}

require_once("./includes/fetch_post_liked_by_users.php");
require_once("./includes/fetch_is_post_liked_by_user.php");
require_once("./includes/fetch_comments_by_post.php");
?>

<div id="container" class="mx-auto my-3">
    <div class="text-center font-weight-bold <?= $is_own_profile ? 'mb-3' : 'mb-2' ?>">
        <?= $profile_name ?>
    </div>

    <?php if (!$is_own_profile) { ?>
        <div class="text-center mb-3">
            <?= fetch_friendship_button($profile_id, friendship_status_code($db, $own_id, $profile_id)); ?>

            <span>
                <a href="message.php?id=<?= $profile_id ?>" class="btn btn-sm btn-info">Message <i class="fas fa-envelope"></i></a>
            </span>
        </div>
        <div class="text-center mb-3">
            <a href="mutual_friends.php?id=<?= $profile_id ?>">
                <span class="text-secondary">
                    <?= print_array_count($mutual_friends, "mutual friend", false) ?>
                </span>
            </a>
        </div>
    <?php } ?>

    <?php if ($is_own_profile) {
        require_once("./includes/form_create_post.php");
    } ?>

    <?php if (empty($posts)) { ?>
        <div class="card p-3 my-2">
            It looks like there are no posts available to view at this moment.
        </div>
    <?php } else {
        foreach ($posts as $post) {
            echo load_post($db, $post, $own_id);
        }
    } ?>
</div>

<?php include("./includes/modal_liked_users.php"); ?>
<?php include("./includes/modal_share_post.php"); ?>

<?php include("./includes/footer.php"); ?>
<script src="assets/js/postActions.js"></script>