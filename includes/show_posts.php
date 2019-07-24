<?php require_once(__DIR__ . "/fetch_post_liked_by_users.php") ?>
<?php require_once(__DIR__ . "/fetch_is_post_liked_by_user.php") ?>
<?php require_once(__DIR__ . "/fetch_comments_by_post.php"); ?>
<?php require_once(__DIR__ . "/generic_functions.php"); ?>

<?php foreach ($posts as $post) {
    echo load_post($db, $post, $profile_id);
} ?>