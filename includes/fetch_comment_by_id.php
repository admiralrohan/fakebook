<?php
function comment_by_id(mysqli $db, int $comment_id): Comment
{
    $query = "SELECT comment_id, comment_content, c.post_id, comment_owner as comment_owner_id, CONCAT(fname, ' ', lname) as comment_owner_name, commented_on
    from comments as c INNER JOIN users as u
    ON c.comment_owner = u.user_id
    where comment_id = '$comment_id'";

    $result = $db->query($query);
    while ($row = $result->fetch_object()) {
        $comment = new Comment($row->comment_id, $row->comment_content, $row->post_id, $row->comment_owner_id, $row->comment_owner_name, $row->commented_on);
    }

    return $comment;
}
