<?php
$post_liked_by_users = post_liked_by_users($db, $post->id);
$is_post_liked_by_user = is_post_liked_by_user($db, $post->id, $own_id);
$comments = comments_by_post($db, $post->id);
?>

<div class="card my-2" id="post-<?= $post->id ?>" data-id="<?= $post->id ?>">
    <div class="card-header">
        <?php if ($own_id == $post->owner->id) { ?>
        <div class="float-right post-crud-options">
            <div class="dropdown d-inline-block">
                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="edit-delete-Button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="edit-delete-button">
                    <a class="dropdown-item edit-post" href="#">Edit Post</a>
                    <a class="dropdown-item delete-post" href="#">Delete Post</a>
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
        <div class="post-content">
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
        </div>

        <div class="row mt-2">
            <div class="col-6 text-left">
                <a href="#" class="post-like-count" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php foreach ($post_liked_by_users as $liked_user) echo "<span data-id='{$liked_user->id}' data-status='{$liked_user->friendshipStatus}'>{$liked_user->name}</span><br>"; ?>" data-likes=<?= count($post_liked_by_users) ?>>
                    <span class="text-secondary">
                        <?= print_array_count($post_liked_by_users, "like") ?>
                    </span>
                </a>
            </div>

            <div class="col-6 text-right">
                <a href="#" class="comment-count">
                    <span class="text-secondary">
                        <?= print_array_count($comments, "comment") ?>
                    </span>
                </a>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-12 col-sm-4 text-center">
                <a href="#" class="btn btn-sm <?= $is_post_liked_by_user ? 'btn-primary' : 'btn-outline-primary' ?> like-post" data-is-liked="<?= $is_post_liked_by_user ?>">Like <i class="fas fa-thumbs-up"></i></a>
            </div>
            <div class="w-100 my-1 d-sm-none"></div>

            <div class="col-12 col-sm-4 text-center">
                <a href="#" class="btn btn-sm btn-outline-primary comment-post" id="comment-link">Comment <i class="fas fa-comments"></i></a>
            </div>
            <div class="w-100 my-1 d-sm-none"></div>

            <div class="col-12 col-sm-4 text-center">
                <a href="#" class="btn btn-sm btn-outline-primary share-post" id="share-button">Share <i class="fas fa-share"></i></a>
            </div>
        </div>

        <hr>
        <div class="my-1">
            <div class="form-group">
                <textarea type="text" class="form-control form-control-sm comment-input" rows="1" placeholder="Write a comment..." name="comment" required><?= isset($_POST["comment"]) ? $_POST["comment"] : '' ?></textarea>
            </div>
        </div>

        <!-- Show Comments -->
        <div class="comments">
            <?php foreach ($comments as $comment) {
                echo load_comment($db, $comment, $own_id);
            } ?>
        </div>
    </div>
</div>