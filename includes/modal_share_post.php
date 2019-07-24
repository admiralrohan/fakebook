<!-- Modal for share post -->
<div class="modal fade" id="share-post-modal" tabindex="-1" role="dialog" aria-labelledby="share post" aria-hidden="true">
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