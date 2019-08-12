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

$comment_content = filter_var($_POST["content"], FILTER_SANITIZE_STRING);
$post_id = $_POST["id"];
$user_id = (int) $_SESSION["user_id"];

if (empty($comment_content)) {
    $errors[] = "You have to write something to comment";
}

if (empty($errors) && isset($post_id)) {
    try {
        $query = "INSERT into comments (comment_id, comment_content, post_id, comment_owner, commented_on) VALUES (NULL, ?, ?, ?, NOW())";

        $stmt = $db->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('sii', $comment_content, $post_id, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {
            $res = array_merge($res, ["success" => true]);
            $res = array_merge($res, ["commentId" => $stmt->insert_id]);
            $res = array_merge($res, ["commentContent" => nl2br($comment_content)]);
            $res = array_merge($res, ["msg" => "Comment posted successfully"]);
        } else {
            $res = array_merge($res, ["success" => false]);
            $res = array_merge($res, ["msg" => "Operation couldn't completed due to database failure"]);
        }
    } catch (Exception $e) {
        $res = array_merge($res, ["success" => false]);
        $res = array_merge($res, ["msg" => "Operation couldn't completed due to database failure"]);
    } catch (Error $e) {
        $res = array_merge($res, ["success" => false]);
        $res = array_merge($res, ["msg" => "Operation couldn't completed due to database failure"]);
    }
} else {
    $res = array_merge($res, ["success" => false]);
    $res = array_merge($res, ["msg" => "Provide id value of the comment or comment content is empty"]);
}

echo json_encode($res);
exit();
