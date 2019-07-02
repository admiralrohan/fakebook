<?php
function posts_by_user($db, $profile_id) {
    $posts = [];

    $query = "SELECT post_id, post_content, user_id AS post_owner_id, CONCAT(fname, ' ', lname) AS post_owner_name, posted_on ";
    $query .= "FROM posts AS p INNER JOIN users AS u ";
    $query .= "ON p.post_owner = u.user_id ";
    $query .= "WHERE p.post_owner = '$profile_id' ";
    $query .= "ORDER BY p.posted_on DESC";

    $result = $db->query($query);
    while ($row = $result->fetch_object()) {
        $posts[] = new Post($row->post_id, $row->post_content, $row->post_owner_id, $row->post_owner_name, $row->posted_on);
    }

    return $posts;
}