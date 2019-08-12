<?php
function comments_by_post(mysqli $db, int $post_id): array
{
    $comments = [];

    $query = "SELECT comment_id, comment_content, c.post_id, comment_owner as comment_owner_id, CONCAT(fname, ' ', lname) as comment_owner_name, commented_on
    from comments as c INNER JOIN users as u
    ON c.comment_owner = u.user_id
    where c.post_id = '$post_id'
    order by c.commented_on DESC";

    $result = $db->query($query);
    echo $db->error;
    while ($row = $result->fetch_object()) {
        $comments[] = new Comment($row->comment_id, $row->comment_content, $row->post_id, $row->comment_owner_id, $row->comment_owner_name, $row->commented_on);
    }

    return $comments;
}
