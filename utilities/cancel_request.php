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

    $query = "SELECT request_id from friend_requests where request_from = {$own_id} && request_to = {$friend_id} && request_status = 'pending'";

    $result = $db->query($query);

    // Check if there any incoming friend request from that person
    if ($result->num_rows == 1) {
        $row = $result->fetch_object();

        $query = "UPDATE friend_requests SET request_status = 'rejected', request_on = NOW() where request_id = {$row->request_id}";

        $result = $db->query($query);
        if ($db->affected_rows == 1) {
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