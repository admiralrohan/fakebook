<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (! isset($_SESSION['user_id'])) {
    header("Location: index.php?from=none");
    exit();
}
$title = $_SESSION["fname"] . "'s Friend List";

$messages = array();

require_once("./utilities/connect_to_db.php");
include("./classes/message.class.php");
include("./includes/header.php");
include("./includes/nav.php");

if (! isset($_GET["id"])) {
    header("Location: profile.php");
    exit();
} else {
    $own_id = (int) $_SESSION["user_id"];
    $own_name = $_SESSION["fullname"];
    $friend_id = (int) $_GET["id"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require("./utilities/process_create_message.php");
    }

    // Check if this user_id exists
    // $query = "SELECT CONCAT(fname, ' ', lname) AS user_name from users where user_id = {$friend_id}";

    // $result = $db->query($query);
    // echo $db->error;
    // if ($result->num_rows == 1) {
        // $row = $result->fetch_object();
        // $friend_name = $row->user_name;
        $friend_name = "John Doe";

        $q = "SELECT msg_id, msg_content, msg_from, msg_to, msgd_on from messages where msg_from = {$own_id} && msg_to = {$friend_id} UNION SELECT msg_id, msg_content, msg_from, msg_to, msgd_on from messages where msg_from = {$friend_id} && msg_to = {$own_id}";

        $result = $db->query($q);
        echo $db->error;
        while ($row = $result->fetch_object()) {
            if ($row->msg_from == $own_id) {
                $messages[] = new Message($row->msg_id, $row->msg_content, $row->msg_from, $own_name, $row->msg_to, $friend_name, $row->msgd_on);
            } else {
                $messages[] = new Message($row->msg_id, $row->msg_content, $row->msg_from, $friend_name, $row->msg_to, $own_name, $row->msgd_on);
            }
        }
    // } else {
    //     header("Location: page_not_found.php");
    //     exit();
    // }
}
?>

<div class="w-50 my-3 vertical-center">
    <div class="text-center font-weight-bold mb-2"><?= $friend_name ?></div>

    <div class="card p-3 my-2">
        <?php if (empty($messages)) { ?>
            <div class="card-body text-center">
                <span>Send HI to <?= $friend_name ?>!</span>
            </div>
        </div>
    <?php } else { ?>
        <div class="card-title font-weight-bold text-center"><?= count($messages) ?> Messages</div>
        <hr>

        <div class="card-body">
            <?php foreach ($messages as $message) { ?>
                <div class="card-text">
                    <div>
                        <div class="card-title font-weight-bold">
                            <a href="profile.php?id=<?= $message->from_id ?>" ?><?= $message->from_name ?></a>
                        </div>
                        <div class="card-subtitle"><?= $message->msgd_on ?></div>
                        <div class="card-text my-2"><?= nl2br($message->content) ?></div>
                    </div>
                </div>
                <hr>
            <?php } ?>
        </div>
    <?php } ?>

        <div class="card-footer">
            <form class="form-inline my-2 my-lg-0" method="POST" action="message.php?id=<?= $friend_id ?>">
                <input class="form-control mr-sm-2" type="text" placeholder="Write something..." name="msg_content" aria-label="Message Content">
                <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Message</button>
            </form>
        </div>
    </div>
</div>
<?php include("./includes/footer.php"); ?>