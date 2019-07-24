<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?from=none");
    exit();
}

require_once(__DIR__ . "/connect_to_db.php");

if (!isset($_GET["id"])) {
    header("Location: ../page_not_found.php");
    exit();
} else {
    $original_post_id = (int) $_GET["id"];
    $user_id = (int) $_SESSION["user_id"]; // post to share
    $is_shared_post = true;

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
        header("Location: ../page_not_found.php");
        exit();
    }

    $content = filter_var($_POST["post_content"], FILTER_SANITIZE_STRING);

    try {
        $query = "INSERT into posts (post_id, post_content, original_post, post_owner, posted_on, is_shared_post) VALUES (NULL, ?, ?, ?, NOW(), ?)";
        $stmt = $db->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('siii', $content, $original_post_id, $user_id, $is_shared_post);
        $stmt->execute();
        echo $db->error;

        if ($stmt->affected_rows == 1) {
            $success[] = "Your post has created successfully.";

            header("Location: ../post.php?id=" . $stmt->insert_id);
            exit();
        } else {
            $errors[] = $db->error;
        }
    } catch (Exception $e) {
        $errors[] = "Exception: " . $e;
    } catch (Error $e) {
        $errors[] = "Error: " . $e;
    }
}
