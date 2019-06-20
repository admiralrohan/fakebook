<?php foreach($posts as $post) { ?>
    <div class="card p-3 my-2">
        <div class="card-title font-weight-bold">
            <a href="<?= "profile.php?id={$post->post_owner_id}" ?>"><?= $post->post_owner_name ?></a>
        </div>
        <div class="card-subtitle"><?= $post->posted_on ?></div>
        <div class="card-text my-2"><?= nl2br(mb_substr($post->post_content, 0, 1000)) . "<br><br><a href='post.php?id={$post->post_id}'>See Full Story</a>" ?></div>

        <?php
        $liked_users = [];
        $q = "SELECT user_id, CONCAT(fname, ' ', lname) as user_name from users where user_id IN
        (SELECT post_liked_by from post_liked_by_users where post_id = {$post->post_id})";
        $result = $db->query($q);

        while ($row = $result->fetch_object()) {
            $liked_users[] = new User($row->user_id, $row->user_name);
        }
        ?>

        <div>
            <a href="post_liked_by.php?id=<?= $post->post_id ?>">
                <span class="text-secondary">
                    <?php echo count($mutual_friend_list) === 0 ? "No" : count($mutual_friend_list) ?> like
                    <?php echo count($mutual_friend_list) > 1 ? "s" : "" ?>
                    <?php echo count($mutual_friend_list) === 0 ? " yet" : "" ?>
                </span>
            </a>
        </div>

        <hr>
        <div class="row">
            <div class="col-sm-4 text-center">
                <a href="#" class="btn btn-sm btn-primary">Like <i class="fas fa-thumbs-up"></i></a>
            </div>
            <div class="col-sm-4 text-center">
                <a href="#" class="btn btn-sm btn-primary">Comment <i class="fas fa-comments"></i></a>
            </div>
            <div class="col-sm-4 text-center">
                <a href="#" class="btn btn-sm btn-primary">Share <i class="fas fa-share"></i></a>
            </div>
        </div>
    </div>
<?php } ?>