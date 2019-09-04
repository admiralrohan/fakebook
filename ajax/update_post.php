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

require_once("./../utilities/connect_to_db.php");

$profile_id = (int) $_SESSION["user_id"];
$post_id = (int) $_POST["id"];
$post_content = filter_var($_POST["content"], FILTER_SANITIZE_STRING);

if (empty($post_content)) {
    $errors[] = "You have to write something to post";
}

if (empty($errors) && isset($post_id)) {
    $query = "SELECT post_content from posts where post_id = {$post_id} && post_owner = {$profile_id}";

    $result = $db->query($query);

    if ($result->num_rows == 1) {
        $query = "UPDATE posts SET post_content = '{$post_content}' where post_id = {$post_id}";

        $result = $db->query($query);
        if ($db->affected_rows == 1) {
            $res = array_merge($res, ["success" => true]);
            $res = array_merge($res, ["content" => nl2br($post_content)]);
            $res = array_merge($res, ["msg" => "Post updated successfully"]);
        } else {
            $res = array_merge($res, ["success" => false]);
            $res = array_merge($res, ["msg" => "Operation couldn't completed due to database failure"]);
        }

        $db->close();
    } else {
        $res = array_merge($res, ["success" => false]);
        $res = array_merge($res, ["msg" => "Invalid operation"]);
    }
} else {
    $res = array_merge($res, ["success" => false]);
    $res = array_merge($res, ["msg" => "Provide id value of the post or post content is empty"]);
}

echo json_encode($res);
exit();
