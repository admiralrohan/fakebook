<?php include(__DIR__ . "/../includes/fetch_friendship_button.php"); ?>
<?php foreach ($post_liked_by_users as $liked_user) { ?>
    <div class="row">
        <div class="col-8 text-left">
            <a href="profile.php?id=<?= $liked_user["id"] ?>" ?><?= $liked_user["name"] ?></a>
        </div>

        <div class="col-4 text-right">
            <?= fetch_friendship_button($liked_user["id"], $liked_user["friendshipStatus"]) ?>
        </div>
    </div>
    <hr>
<?php } ?>