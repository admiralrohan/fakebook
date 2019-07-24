<!-- Modal for liked users -->
<div class="modal fade" id="post-liked-by-modal" tabindex="-1" role="dialog" aria-labelledby="postLikedBy" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-center">
                    <?= print_array_count($post_liked_by_users, "like") ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <?php foreach ($post_liked_by_users as $liked_user) { ?>
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