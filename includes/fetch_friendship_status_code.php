<?php
function friendship_status_code(mysqli $db, int $own_id, int $profile_id): int
{
    // Initialized for the case own_id = profile_id, otherwise it will send null and generate error at the time of user object creation
    $friendship_status_code = 0;

    // Add Friend
    $q = "SELECT request_id from friend_requests where (request_from = {$own_id} && request_to = {$profile_id}) && (request_status = 'pending' || request_status = 'accepted') UNION
    SELECT request_id from friend_requests where (request_from = {$profile_id} && request_to = {$own_id}) && (request_status = 'pending' || request_status = 'accepted')";
    $result = $db->query($q);

    if ($result->num_rows == 0 && $profile_id != $own_id) {
        $friendship_status_code = 1;
    }

    // Friend
    $q = "SELECT request_id from friend_requests where request_from = {$profile_id} && request_to = {$own_id} && request_status = 'accepted' UNION
    SELECT request_id from friend_requests where request_to = {$profile_id} && request_from = {$own_id} && request_status = 'accepted'";
    $result = $db->query($q);

    if ($result->num_rows == 1) {
        $friendship_status_code = 2;
    }

    // Friend request sent
    $q = "SELECT request_id from friend_requests where request_to = {$profile_id} && request_from = {$own_id} && request_status = 'pending'";
    $result = $db->query($q);

    if ($result->num_rows) {
        $friendship_status_code = 3;
    }

    // Respond to friend request
    $q = "SELECT request_id from friend_requests where request_from = {$profile_id} && request_to = {$own_id} && request_status = 'pending'";
    $result = $db->query($q);

    if ($result->num_rows) {
        $friendship_status_code = 4;
    }

    return $friendship_status_code;
}
