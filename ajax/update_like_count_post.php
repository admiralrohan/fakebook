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

$users = isset($_POST["users"]) ? $_POST["users"] : [];

$res = array_merge($res, ["success" => true]);
$res = array_merge($res, ["msg" => "Content fetched successfully"]);
$res = array_merge($res, ["content" => load_class_like_count_post($users)]);

echo json_encode($res);
exit();
