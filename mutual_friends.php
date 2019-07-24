<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$title = $_SESSION["user_name"];
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?from=none");
    exit();
}

require_once("./utilities/connect_to_db.php");
include("./classes/user.class.php");

include("./includes/header.php");
include("./includes/nav.php");

if (!isset($_GET["id"])) {
    header("Location: profile.php");
    exit();
}

$profile_id = (int) $_SESSION["user_id"];
$friend_id = (int) $_GET["id"];

if ($profile_id === $friend_id) {
    header("Location: profile.php");
    exit();
}

// Check if this user_id exists
$query = "SELECT fname from users where user_id = {$friend_id}";

$result = $db->query($query);
echo $db->error;
if ($result->num_rows == 1) {
    $row = $result->fetch_object();
    $friend_name = $row->fname;

    require("./includes/fetch_mutual_friend_count.php");
    $mutual_friends = mutual_friends($db, $profile_id, $friend_id);
} else {
    header("Location: page_not_found.php");
    exit();
}
?>

<div class="w-50 my-3 vertical-center">
    <?php if (empty($mutual_friends)) { ?>
        <div class="card p-3 my-2">
            <div class="card-title text-center my-0">
                <a href="profile.php?id=<?= $friend_id ?>">Back to Friend's Profile</a>
            </div>
            <hr>
            <div class="card-body">
                <span>It looks like you don't have any mutual friend with <?= $friend_name ?></span>
            </div>
        </div>
    <?php } else { ?>
        <div class="card p-3 my-2">
            <div class="card-title font-weight-bold text-center"><?= count($mutual_friends) ?> Mutual Friend <?php echo count($mutual_friends) > 1 ? "s" : "" ?> with <?= $friend_name ?></div>

            <div class="card-subtitle text-center">
                <a href="profile.php?id=<?= $friend_id ?>">Go back to friend's profile</a>
            </div>
            <hr>

            <div class="card-body">
                <?php foreach ($mutual_friends as $mutual_friend) { ?>
                    <div class="card-text row">
                        <div class="col-sm-8 text-left">
                            <a href="profile.php?id=<?= $mutual_friend->id ?>" ?><?= $mutual_friend->name ?></a>
                        </div>

                        <div class="col-sm-4 text-right">
                            <a href="./utilities/unfriend.php?id=<?= $mutual_friend->id ?>" class="btn btn-sm btn-secondary">Unfriend</a>
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