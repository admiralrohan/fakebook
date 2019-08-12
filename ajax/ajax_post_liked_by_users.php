<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$res = [];
if (!isset($_SESSION['user_id'])) {
    $res = array_merge($res, ["success" => false]);
    $res = array_merge($res, ["msg" => "Session timed out"]);
    $res = array_merge($res, ["errorCode" => 0]);
    echo json_encode($res);
    exit();
}

require_once("./../utilities/connect_to_db.php");
require_once("./../classes/user.class.php");
require_once("./../includes/fetch_post_liked_by_users.php");

// Is post id present
if (!isset($_POST["id"])) {
    $res = array_merge($res, ["success" => false]);
    $res = array_merge($res, ["msg" => "Provide id value of the post"]);
} else {
    $post_id = (int) $_POST["id"];

    $post_liked_by_users = post_liked_by_users($db, $post_id);

    $res = array_merge($res, ["success" => true]);
    $res = array_merge($res, ["msg" => "Liked users fetched successfully"]);
    $res = array_merge($res, ["users" => $post_liked_by_users]);
}

echo json_encode($res);
exit();
