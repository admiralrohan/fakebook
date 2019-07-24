<?php
// $comment_liked_by_users = comment_liked_by_users($db, $comment->id);
// $is_comment_liked_by_user = is_comment_liked_by_user($db, $comment->id, $own_id);
$is_comment_liked_by_user = false;
?>

<div>
    <div class="mb-1">
        <span class="font-weight-bold"><a href="profile.php?id=<?= $comment->owner->id ?>" ?><?= $comment->owner->name ?></a></span>
        <span class="my-2"><?= nl2br($comment->content) ?></span>
    </div>

    <div>
        <span>
            <a href="#" class="<?php echo $is_comment_liked_by_user ? 'text-primary' : 'text-secondary' ?>">Like</a>
        </span>
        <?php if ($own_id == $comment->owner->id) { ?>
            &#8208;
            <span>
                <a href="#" class="text-info edit-comment">Edit</a>
            </span>
            &#8208;
            <span>
                <a href="#" class="text-danger delete-comment">Delete</a>
            </span>
        <?php } ?>
        <div class="w-100 my-1 d-sm-none"></div>
        <span class="float-sm-right"><?= $comment->time ?></span>
    </div>
</div>
<hr>