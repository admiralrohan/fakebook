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

include_once("./includes/fetch_post_liked_by_users.php");
include_once("./includes/fetch_is_post_liked_by_user.php");
include_once("./includes/fetch_comments_by_post.php");

// Check if post_id exists and if exists then if the provided id is valid
if (!isset($_GET["id"])) {
    header("Location: page_not_found.php");
    exit();
} else {
    $id = (int) $_GET["id"];
    $own_id = (int) $_SESSION["user_id"];

    $query = "SELECT post_id, post_content, u.user_id AS post_owner_id, p.original_post as original_post_id, CONCAT(fname, ' ', lname) AS post_owner_name, posted_on, p.is_shared_post ";
    $query .= "FROM posts AS p INNER JOIN users AS u ";
    $query .= "ON p.post_owner = u.user_id ";
    $query .= "WHERE p.post_id = '$id' ";

    $result = $db->query($query);
    if ($result->num_rows == 1) {
        $row = $result->fetch_object();

        if (!$row->is_shared_post) {
            $post = new Post(
                $row->post_id,
                $row->post_content,
                $row->post_owner_id,
                $row->post_owner_name,
                $row->posted_on
            );
        } else {
            $id = (int) $row->original_post_id;

            // Original post has not been deleted
            if ($id) {
                $query = "SELECT post_id, post_content, u.user_id AS post_owner_id, p.original_post as original_post_id, CONCAT(fname, ' ', lname) AS post_owner_name, posted_on, p.is_shared_post ";
                $query .= "FROM posts AS p INNER JOIN users AS u ";
                $query .= "ON p.post_owner = u.user_id ";
                $query .= "WHERE p.post_id = '$id' ";

                $result = $db->query($query);
                if ($result->num_rows == 1) {
                    $new_row = $result->fetch_object();

                    if (!$new_row->is_shared_post) {
                        $post = new Post(
                            $row->post_id,
                            $row->post_content,
                            $row->post_owner_id,
                            $row->post_owner_name,
                            $row->posted_on,
                            new Post(
                                $new_row->post_id,
                                $new_row->post_content,
                                $new_row->post_owner_id,
                                $new_row->post_owner_name,
                                $new_row->posted_on
                            ),
                            $row->is_shared_post
                        );
                    } else {
                        header("Location: page_not_found.php");
                        exit();
                    }
                } else {
                    header("Location: page_not_found.php");
                    exit();
                }
            } else { // Original post has been deleted
                $post = new Post(
                    $row->post_id,
                    $row->post_content,
                    $row->post_owner_id,
                    $row->post_owner_name,
                    $row->posted_on,
                    $row->original_post_id,
                    $row->is_shared_post
                );
            }
        }

        $post_owner = new User($post->owner->id, $post->owner->name);
        $title = $post_owner->name . "'s Post";

        $liked_users = post_liked_by_users($db, $post->id);
        $is_post_liked_by_user = is_post_liked_by_user($db, $post->id, (int) $_SESSION["user_id"]);

        $comments = comments_by_post($db, $post->id);
    } else {
        header("Location: page_not_found.php");
        exit();
    }
}
include("./includes/header.php");
include("./includes/nav.php");
?>

<div class="w-50 my-3 vertical-center">
    <div class="card my-2">
        <div class="card-header">
            <?php if ($own_id == $post_owner->id) { ?>
            <div class="float-right">
                <div class="dropdown d-inline-block">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="edit-delete-Button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="edit-delete-button">
                        <a class="dropdown-item" href="#">Edit Post</a>
                        <a class="dropdown-item" href="#">Delete Post</a>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="font-weight-bold">
                <a href="profile.php?id=<?= $post->owner->id ?>"><?= $post->owner->name ?></a>
            </div>
            <div><?= get_date_for_database($post->time) ?></div>
        </div>

        <div class="card-body">
            <?= print_html($post) ?>
            <div class="card-text"><?= nl2br($post->content) ?></div>

            <?php if ($post->isSharedPost && $post->originalPost) { ?>
                <div class="card my-3">
                    <div class="card-header">
                        <div class="font-weight-bold">
                            <a href="profile.php?id=<?= $post->originalPost->owner->id ?>"><?= $post->originalPost->owner->name ?></a>
                        </div>
                        <div><?= get_date_for_database($post->originalPost->time) ?></div>
                    </div>

                    <div class="card-body">
                        <div class="card-text"><?= nl2br($post->originalPost->content) ?></div>
                    </div>
                </div>
            <?php } ?>

            <?php if ($post->isSharedPost && !$post->originalPost) { ?>
                <div class="card my-3">
                    <div class="card-header font-weight-bold">
                        The content isn't available right now
                    </div>

                    <div class="card-body">
                        <div class="card-text">When this happens, this is because the owner only shared it with a small group of people, changed who can see it or it's been deleted.</div>
                    </div>
                </div>
            <?php } ?>

            <div class="row mt-2">
                <div class="col-sm-6 text-left">
                    <a href="#" class="no-of-likes" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php foreach ($liked_users as $liked_user) { ?>
                            <?= $liked_user->name ?><br>
                    <?php } ?>">
                        <span class="text-secondary">
                            <?= print_array_count($liked_users, "like") ?>
                        </span>
                    </a>
                </div>

                <div class="col-sm-6 text-right">
                    <a href="#" class="no-of-comments">
                        <span class="text-secondary">
                            <?= print_array_count($comments, "comment") ?>
                        </span>
                    </a>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-sm-4 text-center">
                    <a href="utilities/<?php echo $is_post_liked_by_user ? 'dis' : '' ?>like_post.php?id=<?= $post->id ?>" class="btn btn-sm <?php echo $is_post_liked_by_user ? 'btn-primary' : 'btn-outline-primary' ?>">Like <i class="fas fa-thumbs-up"></i></a>
                </div>
                <div class="col-sm-4 text-center">
                    <a href="#" class="btn btn-sm btn-outline-primary" id="comment-link">Comment <i class="fas fa-comments"></i></a>
                </div>
                <div class="col-sm-4 text-center">
                    <a href="utilities/share_post.php?id=<?= $post->id ?>" class="btn btn-sm btn-outline-primary" id="share-button">Share <i class="fas fa-share"></i></a>
                </div>
            </div>

            <hr>
            <div class="my-1">
                <form method="POST" action="utilities/post_comment.php" id="comment-form">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm" id="comment-input" placeholder="Write a comment..." name="comment" value="<?= isset($_POST["comment"]) ? $_POST["comment"] : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control form-control-sm" name="postId" value="<?= $post->id ?>">
                    </div>
                </form>
            </div>

            <!-- Show Comments -->
            <div id="message-body">
                <?php foreach ($comments as $comment) { ?>
                    <div>
                        <div class="mb-1">
                            <span class="font-weight-bold"><a href="profile.php?id=<?= $comment->owner->id ?>" ?><?= $comment->owner->name ?></a></span>
                            <span class="my-2"><?= nl2br($comment->content) ?></span>
                        </div>

                        <div>
                            <span>
                                <a href="utilities/<?php echo $is_post_liked_by_user ? 'dis' : '' ?>like_post.php?id=<?= $post->id ?>" class="<?php echo $is_post_liked_by_user ? 'text-primary' : 'text-secondary' ?>">Like</a>
                            </span>
                            <?php if ($own_id == $comment->owner->id) { ?>
                            &#8208;
                            <span>
                                <a href="utilities/<?php echo $is_post_liked_by_user ? 'dis' : '' ?>like_post.php?id=<?= $post->id ?>" class="text-info">Edit</a>
                            </span>
                            &#8208;
                            <span>
                                <a href="utilities/<?php echo $is_post_liked_by_user ? 'dis' : '' ?>like_post.php?id=<?= $post->id ?>" class="text-danger">Delete</a>
                            </span>
                            <?php } ?>
                            <span class="float-lg-right"><?= $comment->time ?></span>
                        </div>
                    </div>
                    <hr>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal for liked users -->
<div class="modal fade" id="post-liked-by" tabindex="-1" role="dialog" aria-labelledby="postLikedBy" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-center">
                    <?= print_array_count($liked_users, "like") ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <?php foreach ($liked_users as $liked_user) { ?>
                    <div class="row">
                        <div class="col-sm-8 text-left">
                            <a href="profile.php?id=<?= $liked_user->id ?>" ?><?= $liked_user->name ?></a>
                        </div>

                        <div class="col-sm-4 text-right">
                            <?php
                            $own_id = $_SESSION["user_id"];
                            $profile_id = $liked_user->id;

                            include(__DIR__ . "/includes/friendship_status.php");
                            ?>
                        </div>
                    </div>
                    <hr>
                <?php } ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for share post -->
<div class="modal fade" id="share-post" tabindex="-1" role="dialog" aria-labelledby="share post" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-center">
                    Share on Your Timeline
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="utilities/share_post.php?id=<?= $post->id ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <textarea class="form-control" id="post-content" rows="3" name="post_content" placeholder="Say something about this..."></textarea>
                    </div>

                    <div class="card my-2">
                        <?php if (!$post->isSharedPost && !$post->originalPost) { ?>
                            <div class="card-header">
                                <div class="font-weight-bold">
                                    <a href="profile.php?id=<?= $post->owner->id ?>"><?= $post->owner->name ?></a>
                                </div>
                                <div><?= get_date_for_database($post->time) ?></div>
                            </div>

                            <div class="card-body">
                                <div class="card-text"><?= nl2br($post->content) ?></div>
                            </div>
                        <?php } ?>

                        <?php if ($post->isSharedPost && $post->originalPost) { ?>
                            <div class="card-header">
                                <div class="font-weight-bold">
                                    <a href="profile.php?id=<?= $post->originalPost->owner->id ?>"><?= $post->originalPost->owner->name ?></a>
                                </div>
                                <div><?= get_date_for_database($post->originalPost->time) ?></div>
                            </div>

                            <div class="card-body">
                                <div class="card-text"><?= nl2br($post->originalPost->content) ?></div>
                            </div>
                        <?php } ?>

                        <?php if ($post->isSharedPost && !$post->originalPost) { ?>
                            <div class="card-header font-weight-bold">
                                The content isn't available right now
                            </div>

                            <div class="card-body">
                                <div class="card-text">
                                    When this happens, this is because the owner only shared it with a small group of people, changed who can see it or it's been deleted.
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Share Post</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("./includes/footer.php"); ?>
<script>
    $(document).ready(function() {
        $(".no-of-likes").on("click", function(event) {
            event.preventDefault();

            <?php if (count($liked_users)) { ?>
                $('#post-liked-by').modal({
                    show: true
                });
            <?php } ?>
        });

        $("#share-button").on("click", function(event) {
            event.preventDefault();

            $('#share-post').modal({
                show: true
            });
            $("#post-content").focus();
        });

        $(".no-of-comments").on("click", function(event) {
            event.preventDefault();
        });

        $("#comment-link").on("click", function(event) {
            event.preventDefault();
            $("#comment-input").focus();
        });

        $("#comment-input").keypress(function(e) {
            if (e.which == 13) {
                $("form#comment-form").submit();
                return false;
            }
        });

        $('[data-toggle="tooltip"]').tooltip();
    });
</script>