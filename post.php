<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (! isset($_SESSION['user_id'])) {
    header("Location: index.php?from=none");
    exit();
}

require_once("./utilities/connect_to_db.php");
require_once("./classes/post.class.php");
require_once("./classes/user.class.php");
include_once("./includes/fetch_post_liked_by_users.php");
include_once("./includes/fetch_is_post_liked_by_user.php");

// Check if post_id exists and if exists then if the provided id is valid
if (! isset($_GET["id"])) {
    header("Location: page_not_found.php");
    exit();
} else {
    $id = (int) $_GET["id"];
    $query = "SELECT post_id, post_content,  AS user_name from users where user_id = ?";
    $query = "SELECT post_id, post_content, user_id AS post_owner_id, CONCAT(fname, ' ', lname) AS post_owner_name, posted_on ";
    $query .= "FROM posts AS p INNER JOIN users AS u ";
    $query .= "ON p.post_owner = u.user_id ";
    $query .= "WHERE p.post_id = '$id' ";

    $result = $db->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_object();
        $post = new Post($row->post_id, $row->post_content, $row->post_owner_id, $row->post_owner_name, $row->posted_on);

        $profile_id = $row->post_owner_id;
        $profile_name = $row->post_owner_name;
        $title = $row->post_owner_name . "'s Post";

        $liked_users = post_liked_by_users($db, $post->post_id);
        $is_post_liked_by_user = is_post_liked_by_user($db, $post->post_id, (int) $_SESSION["user_id"]);
        $db->close();
    } else {
        header("Location: page_not_found.php");
        exit();
    }
}

$is_own_profile = $profile_id == $_SESSION["user_id"];

include("./includes/header.php");
include("./includes/nav.php");
include_once("./includes/fetch_post_liked_by_users.php");
?>

<div class="w-50 my-3 vertical-center">
    <div class="card p-3 my-2">
        <div class="card-title font-weight-bold">
            <a href="profile.php?id=<?= $post->post_owner_id ?>"><?= $post->post_owner_name ?></a>
        </div>
        <div class="card-subtitle"><?= $post->posted_on ?></div>
        <div class="card-text my-2"><?= nl2br($post->post_content) ?></div>

        <div class="mt-2">
            <a href="post_liked_by.php?id=<?= $post->post_id ?>">
                <span class="text-secondary">
                    <?php echo count($liked_users) === 0 ? "No" : count($liked_users) ?>
                    like<?php echo count($liked_users) > 1 ? "s" : "" ?>
                    <?php echo count($liked_users) === 0 ? " yet" : "" ?>
                </span>
            </a>
        </div>

        <hr>
        <div class="row">
            <div class="col-sm-4 text-center">
                <a href="utilities/<?php echo $is_post_liked_by_user ? 'dis' : '' ?>like_post.php?id=<?= $post->post_id ?>" class="btn btn-sm <?php echo $is_post_liked_by_user ? 'btn-primary' : 'btn-outline-primary' ?>">Like <i class="fas fa-thumbs-up"></i></a>
            </div>
            <div class="col-sm-4 text-center">
                <a href="#" class="btn btn-sm btn-outline-primary">Comment <i class="fas fa-comments"></i></a>
            </div>
            <div class="col-sm-4 text-center">
                <a href="#" class="btn btn-sm btn-outline-primary">Share <i class="fas fa-share"></i></a>
            </div>
        </div>
    </div>
</div>
<?php include("./includes/footer.php"); ?>