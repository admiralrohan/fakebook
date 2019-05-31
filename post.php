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
        $db->close();
    } else {
        header("Location: page_not_found.php");
        exit();
    }
}

$is_own_profile = $profile_id == $_SESSION["user_id"];

include("./includes/header.php");
include("./includes/nav.php");
?>

<div class="w-50 my-3 vertical-center">
    <div class="card p-3 my-2">
        <div class="card-title font-weight-bold">
            <a href="profile.php?id=<?= $post->post_owner_id ?>"><?= $post->post_owner_name ?></a>
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
</div>
<?php include("./includes/footer.php"); ?>