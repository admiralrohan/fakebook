<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (! isset($_SESSION["user_id"])) {
    header("Location: index.php?from=none");
    exit();
}

require_once("./connect_to_db.php");

if (! isset($_GET["id"])) {
    header("Location: ./../profile.php");
} else {
    $own_id = (int) $_SESSION["user_id"];
    $friend_id = (int) $_GET["id"];

    $query = "SELECT CONCAT(fname, ' ', lname) AS user_name from users where user_id = ?";
    $stmt = $db->stmt_init();
    $stmt->prepare($query);
    $stmt->bind_param('i', $friend_id);
    $stmt->execute();
    $stmt->bind_result($name);
    $stmt->store_result();

    // If the user id exists
    if ($stmt->num_rows) {
        $profile_id = $id;
        $profile_name = $name;
        $stmt->free_result();

        $query = "INSERT into friend_requests (request_id, request_from, request_to, request_on, request_status) VALUES (NULL, ?, ?, NOW(), 'pending')";
        $stmt->prepare($query);
        $stmt->bind_param('ii', $own_id, $friend_id);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {
            header("Location: ./../profile.php?id=" . $friend_id);
        } else {
            header("Location: ./../profile.php?id=" . $friend_id);
        }

        $db->close();
        exit();
    } else {
        header("Location: ./../profile.php");
        exit();
    }
}