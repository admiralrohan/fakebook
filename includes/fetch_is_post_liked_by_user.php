<?php
function is_post_liked_by_user(mysqli $db, int $post_id, int $user_id): int
{
    $q = "SELECT post_liked_on from post_liked_by_users where post_id = {$post_id} && post_liked_by = {$user_id}";
    $result = $db->query($q);

    return $result->num_rows;
}
