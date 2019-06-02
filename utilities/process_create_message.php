<?php
$content = filter_var($_POST["msg_content"], FILTER_SANITIZE_STRING);

if (empty($content)) {
    $errors[] = "You have to write something to message";
}

if (empty($errors)) {
    try {
        $query = "INSERT into messages (msg_id, msg_content, msg_from, msg_to, msgd_on) VALUES (NULL, ?, ?, ?, NOW())";

        $stmt = $db->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('sii', $content, $own_id, $friend_id);
        $stmt->execute();
        echo $db->error;

        if ($stmt->affected_rows == 1) {
            $success[] = "Your post has sent successfully.";
        } else {
            $errors[] = $db->error;
        }

        $db->close();
    } catch (Exception $e) {
        $errors[] = "Exception: " . $e;
    } catch (Error $e) {
        $errors[] = "Error: " . $e;
    }
}