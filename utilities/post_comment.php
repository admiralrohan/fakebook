<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?from=none");
    exit();
}

require_once(__DIR__ . "/connect_to_db.php");

$comment = filter_var($_POST["comment"], FILTER_SANITIZE_STRING);
$post_id = $_POST["postId"];
$user_id = (int) $_SESSION["user_id"];

if (empty($comment)) {
    $errors[] = "You have to write something to comment";
}

if (empty($errors) && isset($post_id)) {
    try {
        $query = "INSERT into comments (comment_id, comment_content, post_id, comment_owner, commented_on) VALUES (NULL, ?, ?, ?, NOW())";

        $stmt = $db->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('sii', $comment, $post_id, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {
            header("Location: ./../post.php?id={$post_id}");
            exit();
        } else {
            $errors[] = $db->error;
        }
    } catch (Exception $e) {
        // $errors[] = "Exception: " . $e;
        echo $e;
    } catch (Error $e) {
        // $errors[] = "Error: " . $e;
        echo $e;
    }
} else {
    header("Location: ./../post.php?id={$post_id}");
    exit();
}