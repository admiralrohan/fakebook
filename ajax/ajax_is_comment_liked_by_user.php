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
require_once("./../includes/fetch_is_comment_liked_by_user.php");

// Is post id present
if (!isset($_POST["id"])) {
    $res = array_merge($res, ["success" => false]);
    $res = array_merge($res, ["msg" => "Provide id value of the comment"]);
} else {
    $comment_id = (int) $_POST["id"];
    $user_id = (int) $_SESSION["user_id"];

    $is_comment_liked_by_user = is_comment_liked_by_user($db, $comment_id, $user_id);

    $res = array_merge($res, ["success" => true]);
    $res = array_merge($res, ["msg" => "Like status for the user fetched successfully"]);
    $res = array_merge($res, ["isLiked" => $is_comment_liked_by_user]);
}

echo json_encode($res);
exit();
