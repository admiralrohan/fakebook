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

require_once(__DIR__ . "/../utilities/connect_to_db.php");
require_once(__DIR__ . "/../classes/comment.class.php");
require_once(__DIR__ . "/../includes/fetch_comment_by_id.php");
require_once(__DIR__ . "/../includes/generic_functions.php");

if (!isset($_POST["id"])) {
    $res = array_merge($res, ["success" => false]);
    $res = array_merge($res, ["msg" => "Provide id value of the comment"]);
} else {
    $id = (int) $_POST["id"];
    $own_id = (int) $_SESSION["user_id"];
    $comment = comment_by_id($db, $id);
    $comment_body = load_comment_without_like($comment, $own_id);

    $res = array_merge($res, ["success" => true]);
    $res = array_merge($res, ["msg" => "Comment body fetched successfully"]);
    $res = array_merge($res, ["commentBody" => $comment_body]);
}

echo json_encode($res);
exit();
