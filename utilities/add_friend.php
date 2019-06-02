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
    if ($stmt->num_rows == 1) {
        $stmt->free_result();

        $query = "SELECT request_id from friend_requests where (request_from = {$own_id} && request_to = {$friend_id}) && (request_status = 'pending' || request_status = 'accepted') UNION SELECT request_id from friend_requests where (request_from = {$friend_id} && request_to = {$own_id}) && (request_status = 'pending' || request_status = 'accepted')";

        $result = $db->query($query);

        // Check if he is already a friend / there already a request from that person / already a request sent to that person, if not send request
        if ($result->num_rows == 0) {
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
    } else {
        header("Location: ./../profile.php");
        exit();
    }
}