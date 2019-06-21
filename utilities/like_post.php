<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (! isset($_SESSION["user_id"])) {
    header("Location: index.php?from=none");
    exit();
}

require_once("./connect_to_db.php");

// Is post id present
if (! isset($_GET["id"])) {
    header("Location: ./../profile.php");
} else {
    $profile_id = (int) $_SESSION["user_id"];
    $post_id = (int) $_GET["id"];

    $query = "SELECT post_liked_on from post_liked_by_users where post_id = {$post_id} && post_liked_by = {$profile_id}";

    $result = $db->query($query);

    if ($result->num_rows == 0) {
        $row = $result->fetch_object();

        $query = "INSERT into post_liked_by_users (post_id, post_liked_by, post_liked_on) VALUES (?, ?, NOW())";

        $stmt = $db->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('ss', $post_id, $profile_id);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {
            header("Location: ./../post.php?id=" . $post_id);
        } else {
            header("Location: ./../post.php?id=" . $post_id);
        }

        $db->close();
        exit();
    } else {
        header("Location: ./../profile.php");
        exit();
    }
}