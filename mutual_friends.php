<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$title = $_SESSION["user_name"];
if (! isset($_SESSION['user_id'])) {
    header("Location: index.php?from=none");
    exit();
}

$friends = array();

require_once("./utilities/connect_to_db.php");
include("./classes/friend.class.php");
include("./includes/header.php");
include("./includes/nav.php");

$profile_id = (int) $_SESSION["user_id"];
$friend_id = (int) $_GET["id"];

$own_friend_list = array();
$friends_friend_list = array();
$mutual_friend_list = array();

$q = "SELECT user_id from users where user_id IN
(SELECT request_from from friend_requests where request_to = {$profile_id} && request_status = 'accepted' UNION
SELECT request_to from friend_requests where request_from = {$profile_id} && request_status = 'accepted')";

$result = $db->query($q);
while ($row = $result->fetch_object()) {
    $own_friend_list[] = $row->user_id;
}
// print_r($own_friend_list);

$q = "SELECT user_id from users where user_id IN
(SELECT request_from from friend_requests where request_to = {$friend_id} && request_status = 'accepted' UNION
SELECT request_to from friend_requests where request_from = {$friend_id} && request_status = 'accepted')";

$result = $db->query($q);
while ($row = $result->fetch_object()) {
    $friends_friend_list[] = $row->user_id;
}
// print_r($friends_friend_list);

foreach($own_friend_list as $own_friend) {
    foreach($friends_friend_list as $friends_friend) {
        if ($own_friend == $friends_friend) {
            $mutual_friend_list[] = $own_friend;
        }
    }
}
print_r($mutual_friend_list);

$q = "SELECT user_id, CONCAT(fname, ' ', lname) as user_name from users where user_id IN {$mutual_friend_list}";

$result = $db->query($q);
echo $db->error;
while ($row = $result->fetch_object()) {
    $friends[] = new Friend($row->user_id, $row->user_name);
}
?>

<div class="w-50 my-3 vertical-center">
    <?php if (empty($friends)) { ?>
        <div class="card p-3 my-2">
            <div class="card-title text-center my-0">
                <a href="profile.php?id=<?= $friend_id ?>">Back to Friend's Profile</a>
            </div>
            <hr>
            <div class="card-body">
                <span>It looks like you don't have any mutual friend with <?= $friend_name ?>.</span>
            </div>
        </div>
    <?php } else { ?>
        <div class="card p-3 my-2">
            <?php if (count($friends) == 1) { ?>
            <div class="card-title font-weight-bold text-center"><?= count($friends) ?> Mutual Friend</div>
            <?php } else { ?>
            <div class="card-title font-weight-bold text-center"><?= count($friends) ?> Mutual Friends</div>
            <?php } ?>
            <div class="card-subtitle text-center">
                <a href="profile.php?id=<?= $friend_id ?>">Go back to friend's profile</a>
            </div>
            <hr>

            <div class="card-body">
                <?php foreach ($friends as $friend) { ?>
                    <div class="card-text row">
                        <div class="col-sm-8 text-left">
                            <a href="profile.php?id=<?= $friend->id ?>" ?><?= $friend->name ?></a>
                        </div>

                        <div class="col-sm-4 text-right">
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