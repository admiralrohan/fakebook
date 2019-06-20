<?php
$query = "SELECT post_id, post_content, user_id AS post_owner_id, CONCAT(fname, ' ', lname) AS post_owner_name, posted_on
FROM posts AS p INNER JOIN users AS u
ON p.post_owner = u.user_id
WHERE p.post_owner IN
(SELECT request_from from friend_requests where request_to = {$profile_id} && request_status = 'accepted' UNION
SELECT request_to from friend_requests where request_from = {$profile_id} && request_status = 'accepted') ORDER BY p.posted_on DESC";

$result = $db->query($query);
echo $db->error;
while ($row = $result->fetch_object()) {
    $posts[] = new Post($row->post_id, $row->post_content, $row->post_owner_id, $row->post_owner_name, $row->posted_on);
}