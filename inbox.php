<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?from=none");
    exit();
}
$title = $_SESSION["fname"] . "'s Inbox";

$friends = [];

require_once("./utilities/connect_to_db.php");
include("./classes/user.class.php");

include("./includes/header.php");
include("./includes/nav.php");

$profile_id = (int) $_SESSION["user_id"];

$q = "SELECT user_id, CONCAT(fname, ' ', lname) as user_name from users where user_id IN
(SELECT request_from from friend_requests where request_to = {$profile_id} && request_status = 'accepted' UNION
SELECT request_to from friend_requests where request_from = {$profile_id} && request_status = 'accepted')";

$result = $db->query($q);
while ($row = $result->fetch_object()) {
    $friends[] = new User($row->user_id, $row->user_name);
}
?>

<div id="container" class="mx-auto my-3">
    <div class="card p-3 my-2">
        This page isn't completed yet! But you can message individual users by going to user's profile page and there is a link to message them directly.
    </div>
</div>
<?php include("./includes/footer.php"); ?>