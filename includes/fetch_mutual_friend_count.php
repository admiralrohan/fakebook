<?php
function mutual_friends($db, $own_id, $friend_id)
{
    $own_friend_list = array();
    $friends_friend_list = array();
    $mutual_friend_list = array();
    $mutual_friends = [];

    $q = "SELECT user_id from users where user_id IN
    (SELECT request_from from friend_requests where request_to = {$own_id} && request_status = 'accepted' UNION
    SELECT request_to from friend_requests where request_from = {$own_id} && request_status = 'accepted')";

    $result = $db->query($q);
    while ($row = $result->fetch_object()) {
        $own_friend_list[] = $row->user_id;
    }

    $q = "SELECT user_id from users where user_id IN
    (SELECT request_from from friend_requests where request_to = {$friend_id} && request_status = 'accepted' UNION
    SELECT request_to from friend_requests where request_from = {$friend_id} && request_status = 'accepted')";

    $result = $db->query($q);
    while ($row = $result->fetch_object()) {
        $friends_friend_list[] = $row->user_id;
    }

    foreach ($own_friend_list as $own_friend) {
        foreach ($friends_friend_list as $friends_friend) {
            if ($own_friend == $friends_friend) {
                $mutual_friend_list[] = $own_friend;
            }
        }
    }

    if (!empty($mutual_friend_list)) {
        $ids = implode(", ", $mutual_friend_list);
        $q = "SELECT user_id, CONCAT(fname, ' ', lname) as user_name from users where user_id IN ({$ids})";

        $result = $db->query($q);
        while ($row = $result->fetch_object()) {
            $mutual_friends[] = new User($row->user_id, $row->user_name);
        }
    }

    return $mutual_friends;
}
