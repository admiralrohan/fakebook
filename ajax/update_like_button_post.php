<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$res = [];
if (!isset($_SESSION["user_id"])) {
    $res = array_merge($res, ["success" => false]);
    $res = array_merge($res, ["msg" => "Session timed out"]);
    $res = array_merge($res, ["errorCode" => 0]);
    echo json_encode($res);
    exit();
}

require_once("./../includes/generic_functions.php");

// Is post id present
if (!isset($_POST["isLiked"])) {
    $res = array_merge($res, ["success" => false]);
    $res = array_merge($res, ["msg" => "Provide users who liked the post"]);
} else {
    $is_liked = (bool) $_POST["isLiked"];

    $res = array_merge($res, ["success" => true]);
    $res = array_merge($res, ["msg" => "Content fetched successfully"]);
    $res = array_merge($res, ["content" => load_button_like_post($is_liked)]);
}

echo json_encode($res);
exit();
