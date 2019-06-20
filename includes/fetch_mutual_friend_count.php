<?php
$own_friend_list = array();
$friends_friend_list = array();
$mutual_friend_list = array();

// Temporary variable for this file for reusing purposes
$profile_id_temp = $_SESSION["user_id"];
$friend_id_temp = $_GET["id"];

$q = "SELECT user_id from users where user_id IN
(SELECT request_from from friend_requests where request_to = {$profile_id_temp} && request_status = 'accepted' UNION
SELECT request_to from friend_requests where request_from = {$profile_id_temp} && request_status = 'accepted')";

$result = $db->query($q);
while ($row = $result->fetch_object()) {
    $own_friend_list[] = $row->user_id;
}

$q = "SELECT user_id from users where user_id IN
(SELECT request_from from friend_requests where request_to = {$friend_id_temp} && request_status = 'accepted' UNION
SELECT request_to from friend_requests where request_from = {$friend_id_temp} && request_status = 'accepted')";

$result = $db->query($q);
while ($row = $result->fetch_object()) {
    $friends_friend_list[] = $row->user_id;
}

foreach($own_friend_list as $own_friend) {
    foreach($friends_friend_list as $friends_friend) {
        if ($own_friend == $friends_friend) {
            $mutual_friend_list[] = $own_friend;
        }
    }
}