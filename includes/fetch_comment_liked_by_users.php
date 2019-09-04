<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?from=none");
    exit();
}

require_once(__DIR__ . "/fetch_friendship_status_code.php");

function comment_liked_by_users(mysqli $db, int $id): array
{
    $liked_users = [];
    $own_id = $_SESSION['user_id'];

    $q = "SELECT user_id, CONCAT(fname, ' ', lname) as user_name from users where user_id IN
    (SELECT comment_liked_by from comment_liked_by_users where comment_id = {$id} ORDER BY comment_liked_on DESC)";
    $result = $db->query($q);

    while ($row = $result->fetch_object()) {
        $friendship_status_code = friendship_status_code($db, $own_id, $row->user_id);
        $liked_users[] = new User($row->user_id, $row->user_name, $friendship_status_code);
    }
    return $liked_users;
}
