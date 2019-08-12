<div class="form-group">
    <textarea class="form-control" id="post-content" rows="3" name="post_content" placeholder="Say something about this..."></textarea>
    <input id="post-id" type="hidden" name="post_id" value="0">
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