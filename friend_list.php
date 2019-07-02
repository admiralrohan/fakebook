<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (! isset($_SESSION['user_id'])) {
    header("Location: index.php?from=none");
    exit();
}
$title = $_SESSION["fname"] . "'s Friend List";

$friends = [];

require_once("./utilities/connect_to_db.php");
include("./classes/user.class.php");
include("./includes/header.php");
include("./includes/nav.php");
include_once("./includes/generic_functions.php");

$profile_id = (int) $_SESSION["user_id"];

$q = "SELECT user_id, CONCAT(fname, ' ', lname) as user_name from users where user_id IN
(SELECT request_from from friend_requests where request_to = {$profile_id} && request_status = 'accepted' UNION
SELECT request_to from friend_requests where request_from = {$profile_id} && request_status = 'accepted')";

$result = $db->query($q);
while ($row = $result->fetch_object()) {
    $friends[] = new User($row->user_id, $row->user_name);
}
?>

<div class="w-50 my-3 vertical-center">
    <?php if (empty($friends)) { ?>
        <div class="card p-3 my-2">
            <div class="card-title text-center my-0">
                <a href="received_friend_requests.php">View Received Requests</a>
            </div>
            <hr>
            <div class="card-body">
                <span>It looks like you don't have any friend at this moment.</span>
            </div>
        </div>
    <?php } else { ?>
        <div class="card p-3 my-2">
            <div class="card-title font-weight-bold text-center">
                <?= print_array_count($friends, "friend") ?>
            </div>
            <div class="card-subtitle text-center">
                <a href="received_friend_requests.php">View Received Requests</a>
            </div>
            <hr>

            <div class="card-body">
                <?php foreach ($friends as $friend) { ?>
                    <div class="card-text row">
                        <div class="col-sm-8 text-left">
                            <a href="profile.php?id=<?= $friend->id ?>" ?><?= $friend->name ?></a>
                            <!-- <span>1 mutual friend</span> -->
                        </div>

                        <div class="col-sm-4 text-right">
                            <a href="message.php?id=<?= $friend->id ?>" class="btn btn-sm btn-info">Message</a>
                            <a href="./utilities/unfriend.php?id=<?= $friend->id ?>" class="btn btn-sm btn-secondary">Unfriend</a>
                        </div>
                    </div>
                    <hr>
                <?php } ?>
            </div>
            </div>
        </div>
    <?php } ?>
</div>
<?php include("./includes/footer.php"); ?>