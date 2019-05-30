<?php
$content = filter_var($_POST["post_content"], FILTER_SANITIZE_STRING);
if (empty($content)) {
    $errors[] = "You have to write something to post";
}

if (empty($errors)) {
    try {
        $query = "INSERT into posts (post_id, post_content, post_owner, posted_on) VALUES (NULL, ?, ?, NOW())";
        $stmt = $db->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('si', $content, $_SESSION["user_id"]);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {
            $success[] = "Your post has created successfully.";
        } else {
            $errors[] = $db->error;
        }

        $db->close();
    } catch (Exception $e) {
        echo "Exception: " . $e;
    } catch (Error $e) {
        echo "Error: " . $e;
    }
}