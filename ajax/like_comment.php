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
    $res = array_merge($res, ["msg" => "Provide id value of the comment"]);
} else {
    $profile_id = (int) $_SESSION["user_id"];
    $comment_id = (int) $_POST["id"];

    $query = "SELECT comment_liked_on from comment_liked_by_users where comment_id = {$comment_id} && comment_liked_by = {$profile_id}";

    $result = $db->query($query);

    if ($result->num_rows == 0) {
        $row = $result->fetch_object();

        $query = "INSERT into comment_liked_by_users (comment_id, comment_liked_by, comment_liked_on) VALUES (?, ?, NOW())";

        $stmt = $db->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('ss', $comment_id, $profile_id);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {
            $res = array_merge($res, ["success" => true]);
            $res = array_merge($res, ["msg" => "Comment liked successfully"]);
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
