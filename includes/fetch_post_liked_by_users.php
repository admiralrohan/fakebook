<?php
function post_liked_by_users($db, $id) {
    $liked_users = [];

    $q = "SELECT user_id, CONCAT(fname, ' ', lname) as user_name from users where user_id IN
    (SELECT post_liked_by from post_liked_by_users where post_id = {$id})";
    $result = $db->query($q);

    while ($row = $result->fetch_object()) {
        $liked_users[] = new User($row->user_id, $row->user_name);
    }
    return $liked_users;
}
