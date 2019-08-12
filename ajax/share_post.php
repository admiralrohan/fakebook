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

if (!isset($_POST["id"])) {
    $res = array_merge($res, ["success" => false]);
    $res = array_merge($res, ["msg" => "Provide id value of the post to share"]);
} else {
    $original_post_id = (int) $_POST["id"];     // Post to share
    $user_id = (int) $_SESSION["user_id"];  // Shared by user
    $is_shared_post = true;     // This value will always be true as we are sharing this post

    // Checking whether this post is already a shared post or not, if yes then fetch it's original id
    $query = "SELECT post_id, post_content, u.user_id AS post_owner_id, p.original_post as original_post_id, CONCAT(fname, ' ', lname) AS post_owner_name, posted_on, p.is_shared_post ";
    $query .= "FROM posts AS p INNER JOIN users AS u ";
    $query .= "ON p.post_owner = u.user_id ";
    $query .= "WHERE p.post_id = '$original_post_id' ";

    $result = $db->query($query);
    if ($result->num_rows == 1) {
        $row = $result->fetch_object();

        if ($row->is_shared_post) {
            $original_post_id = $row->original_post_id;
        }
    } else {
        $res = array_merge($res, ["success" => false]);
        $res = array_merge($res, ["msg" => "Post doesn't exist"]);
    }

    $content = filter_var($_POST["content"], FILTER_SANITIZE_STRING);

    try {
        $query = "INSERT into posts (post_id, post_content, original_post, post_owner, posted_on, is_shared_post) VALUES (NULL, ?, ?, ?, NOW(), ?)";
        $stmt = $db->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('siii', $content, $original_post_id, $user_id, $is_shared_post);
        $stmt->execute();
        echo $db->error;

        if ($stmt->affected_rows == 1) {
            $res = array_merge($res, ["success" => true]);
            $res = array_merge($res, ["msg" => "Your post has created successfully."]);
            $res = array_merge($res, ["newPostUrl" => "post.php?id=" . $stmt->insert_id]);
        } else {
            $errors[] = $db->error;
            $res = array_merge($res, ["success" => false]);
            $res = array_merge($res, ["msg" => "Operation couldn't completed due to databse failure"]);
        }
    } catch (Exception $e) {
        $res = array_merge($res, ["success" => false]);
        $res = array_merge($res, ["msg" => $e]);
    } catch (Error $e) {
        $res = array_merge($res, ["success" => false]);
        $res = array_merge($res, ["msg" => $e]);
    }
}

echo json_encode($res);
exit();
