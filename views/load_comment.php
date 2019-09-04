<?php
$comment_liked_by_users = comment_liked_by_users($db, $comment->id);
$is_comment_liked_by_user = is_comment_liked_by_user($db, $comment->id, $own_id);
?>

<div id="comment-<?= $comment->id ?>" class="comment" data-id="<?= $comment->id ?>">
    <div class="mb-1">
        <span class="comment-owner font-weight-bold">
            <a href="profile.php?id=<?= $comment->owner->id ?>"><?= $comment->owner->name ?></a>
        </span>
        <span class="comment-content my-2"><?= nl2br($comment->content) ?></span>
    </div>

    <div class="comment-actions">
        <span>
            <a href="#" class="<?= $is_comment_liked_by_user ? 'text-primary' : 'text-secondary' ?> like-comment" data-is-liked="<?= $is_comment_liked_by_user ?>">Like</a>
        </span>

        <span class="badge badge-pill badge-primary comment-like-count<?= count($comment_liked_by_users) === 0 ? ' d-none' : '' ?>" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php foreach ($comment_liked_by_users as $liked_user) echo "<span data-id='{$liked_user->id}' data-status='{$liked_user->friendshipStatus}'>{$liked_user->name}</span><br>"; ?>" data-likes=<?= count($comment_liked_by_users) ?>>
            <?= count($comment_liked_by_users) ?>
        </span>

        <?php if ($own_id == $comment->owner->id) { ?>
            <span>
                &#8208;
                <a href="#" class="text-info edit-comment">Edit</a>
            </span>
            <span>
                &#8208;
                <a href="#" class="text-danger delete-comment">Delete</a>
            </span>
        <?php } ?>

        <!-- Used to send comment time to next line for smaller screens -->
        <div class="w-100 my-1 d-sm-none"></div>
        <span class="float-sm-right"><?= $comment->time ?></span>
    </div>
</div>
<hr>