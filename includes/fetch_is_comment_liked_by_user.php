<?php
function is_comment_liked_by_user(mysqli $db, int $comment_id, int $user_id): int
{
    $q = "SELECT comment_liked_on from comment_liked_by_users where comment_id = {$comment_id} && comment_liked_by = {$user_id}";
    $result = $db->query($q);

    return $result->num_rows;
}
