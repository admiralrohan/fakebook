<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?from=none");
    exit();
}

$messages = [];

require_once("./utilities/connect_to_db.php");
include("./classes/message.class.php");

if (!isset($_GET["id"])) {
    header("Location: profile.php");
    exit();
} else {
    $own_id = (int) $_SESSION["user_id"];
    $own_name = $_SESSION["fullname"];
    $friend_id = (int) $_GET["id"];

    // Check if this user_id exists
    $query = "SELECT CONCAT(fname, ' ', lname) AS user_name from users where user_id = {$friend_id}";

    $result = $db->query($query);
    if ($result->num_rows == 1) {
        $row = $result->fetch_object();
        $friend_name = $row->user_name;

        $title = "Chat with {$friend_name}";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require("./utilities/process_create_message.php");
        }

        $q = "SELECT msg_id, msg_content, msg_from, msg_to, msgd_on from messages where msg_from = {$own_id} && msg_to = {$friend_id} UNION SELECT msg_id, msg_content, msg_from, msg_to, msgd_on from messages where msg_from = {$friend_id} && msg_to = {$own_id}";

        $result = $db->query($q);
        while ($row = $result->fetch_object()) {
            if ($row->msg_from == $own_id) {
                $messages[] = new Message($row->msg_id, $row->msg_content, $row->msg_from, $own_name, $row->msg_to, $friend_name, $row->msgd_on);
            } else {
                $messages[] = new Message($row->msg_id, $row->msg_content, $row->msg_from, $friend_name, $row->msg_to, $own_name, $row->msgd_on);
            }
        }
    } else {
        header("Location: page_not_found.php");
        exit();
    }
}

include("./includes/header.php");
include("./includes/nav.php");
include_once("./includes/generic_functions.php");
?>

<div id="container" class="mx-auto my-3">
    <div class="text-center font-weight-bold mb-2"><?= $friend_name ?></div>

    <div class="card p-3 my-2">
        <?php if (empty($messages)) { ?>
            <div class="card-body text-center">
                <span>Send HI to <?= $friend_name ?>!</span>
            </div>
        </div>
    <?php } else { ?>
        <div class="card-title font-weight-bold text-center"><?= print_array_count($messages, "message") ?></div>
        <hr>

        <div class="card-body" id="message-body">
            <?php foreach ($messages as $message) { ?>
                <div class="card-text">
                    <div>
                        <div class="card-title">
                            <span class="font-weight-bold"><a href="profile.php?id=<?= $message->from->id ?>" ?><?= $message->from->name ?></a></span>
                            <span class="float-lg-right"><?= $message->time ?></span>
                        </div>
                        <div class="card-text my-2"><?= nl2br($message->content) ?></div>
                    </div>
                </div>
                <hr>
            <?php } ?>
        </div>
    <?php } ?>

    <div class="card-footer">
        <form id="message-form" method="POST" action="message.php?id=<?= $friend_id ?>">
            <input class="form-control form-control-sm" type="text" id="message" placeholder="Type your message..." name="message" aria-label="Message">
        </form>
    </div>
</div>
</div>
<?php include("./includes/footer.php"); ?>
<script>
    $(document).ready(function() {
        $("#message").keypress(function(e) {
            if (e.which == 13) {
                $("form#message-form").submit();
                return false;
            }
        });
    });
</script>