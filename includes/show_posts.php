<?php include_once(__DIR__ . "/fetch_post_liked_by_users.php") ?>
<?php include_once(__DIR__ . "/fetch_is_post_liked_by_user.php") ?>
<?php include_once(__DIR__ . "/generic_functions.php"); ?>

<?php foreach($posts as $post) { ?>
    <div class="card p-3 my-2">
        <div class="card-title font-weight-bold">
            <a href="<?= "profile.php?id={$post->owner->id}" ?>"><?= $post->owner->name ?></a>
        </div>
        <div class="card-subtitle"><?= $post->time ?></div>
        <div class="card-text my-2"><?= nl2br(mb_substr($post->content, 0, 1000)) . "<br><br><a href='post.php?id={$post->id}'>See Full Story</a>" ?></div>

        <?php
            $liked_users = post_liked_by_users($db, $post->id);
            $is_post_liked_by_user = is_post_liked_by_user($db, $post->id, (int) $_SESSION["user_id"]);
        ?>

        <div>
            <a href="post_liked_by.php?id=<?= $post->id ?>">
                <span class="text-secondary">
                    <?= print_array_count($liked_users, "like") ?>
                </span>
            </a>
        </div>

        <hr>
        <div class="row">
            <div class="col-sm-4 text-center">
            <a href="utilities/<?php echo $is_post_liked_by_user ? 'dis' : '' ?>like_post.php?id=<?= $post->id ?>" class="btn btn-sm <?php echo $is_post_liked_by_user ? 'btn-primary' : 'btn-outline-primary' ?>">Like <i class="fas fa-thumbs-up"></i></a>
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