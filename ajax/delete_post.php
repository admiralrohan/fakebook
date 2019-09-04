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

// Is post id present
if (!isset($_POST["id"])) {
    $res = array_merge($res, ["success" => false]);
    $res = array_merge($res, ["msg" => "Provide id value of the post"]);
} else {
    $profile_id = (int) $_SESSION["user_id"];
    $post_id = (int) $_POST["id"];

    $query = "SELECT posted_on from posts where post_id = {$post_id} && post_owner = {$profile_id}";

    $result = $db->query($query);

    if ($result->num_rows == 1) {
        $query = "DELETE FROM posts where post_id = {$post_id} && post_owner = {$profile_id}";

        $result = $db->query($query);
        if ($db->affected_rows == 1) {
            $res = array_merge($res, ["success" => true]);
            $res = array_merge($res, ["msg" => "Post deleted successfully"]);
        } else {
            $res = array_merge($res, ["success" => false]);
            $res = array_merge($res, ["msg" => "Operation couldn't completed due to database failure"]);
        }

        $db->close();
    } else {
        $res = array_merge($res, ["success" => false]);
        $res = array_merge($res, ["msg" => "Invalid operation"]);
    }
}

echo json_encode($res);
exit();
