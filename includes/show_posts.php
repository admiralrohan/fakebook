<?php include_once(__DIR__ . "/fetch_post_liked_by_users.php") ?>
<?php include_once(__DIR__ . "/fetch_is_post_liked_by_user.php") ?>

<?php foreach($posts as $post) { ?>
    <div class="card p-3 my-2">
        <div class="card-title font-weight-bold">
            <a href="<?= "profile.php?id={$post->post_owner_id}" ?>"><?= $post->post_owner_name ?></a>
        </div>
        <div class="card-subtitle"><?= $post->posted_on ?></div>
        <div class="card-text my-2"><?= nl2br(mb_substr($post->post_content, 0, 1000)) . "<br><br><a href='post.php?id={$post->post_id}'>See Full Story</a>" ?></div>

        <?php
            $liked_users = post_liked_by_users($db, $post->post_id);
            $is_post_liked_by_user = is_post_liked_by_user($db, $post->post_id, (int) $_SESSION["user_id"]);
        ?>

        <div>
            <a href="post_liked_by.php?id=<?= $post->post_id ?>">
                <span class="text-secondary">
                    <?php echo count($liked_users) === 0 ? "No" : count($liked_users) ?>
                    like<?php echo count($liked_users) > 1 ? "s" : "" ?>
                    <?php echo count($liked_users) === 0 ? " yet" : "" ?>
                </span>
            </a>
        </div>

        <hr>
        <div class="row">
            <div class="col-sm-4 text-center">
                <a href="#" class="btn btn-sm <?php echo $is_post_liked_by_user ? 'btn-primary' : 'btn-outline-primary' ?>">Like <i class="fas fa-thumbs-up"></i></a>
            </div>
            <div class="col-sm-4 text-center">
                <a href="#" class="btn btn-sm btn-outline-primary">Comment <i class="fas fa-comments"></i></a>
            </div>
            <div class="col-sm-4 text-center">
                <a href="#" class="btn btn-sm btn-outline-primary">Share <i class="fas fa-share"></i></a>
            </div>
        </div>
    </div>
<?php } ?>