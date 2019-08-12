<?php
$is_comment_liked_by_user = false;
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
            <a href="#" class="<?php echo $is_comment_liked_by_user ? 'text-primary' : 'text-secondary' ?> like-comment">Like</a>
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