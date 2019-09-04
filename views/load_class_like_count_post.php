<a href="#" class="post-like-count" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php foreach ($post_liked_by_users as $liked_user) echo "<span data-id='{$liked_user["id"]}' data-status='{$liked_user["friendshipStatus"]}'>{$liked_user["name"]}</span><br>"; ?>" data-likes=<?= count($post_liked_by_users) ?>>
    <span class="text-secondary">
        <?= print_array_count($post_liked_by_users, "like") ?>
    </span>
</a>