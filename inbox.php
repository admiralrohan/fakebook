<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 'On');
ini_set('html_errors', 1);
error_reporting(-1);

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?from=none");
    exit();
}
$title = $_SESSION["fname"] . "'s Inbox";

$contacts = [];
$messages = [];

require_once("./utilities/connect_to_db.php");
include("./classes/user.class.php");
include("./classes/message.class.php");

include("./includes/header.php");
include("./includes/nav.php");
include_once("./includes/generic_functions.php");

$profile_id = (int) $_SESSION["user_id"];
$profile_name = $_SESSION["fullname"];

$q = "SELECT msg_id, msg_content, msg_from, msg_to, msgd_on from messages where msg_from = {$profile_id} || msg_to = {$profile_id} order by msgd_on asc";

$result = $db->query($q);
while ($row = $result->fetch_object()) {
    if ($row->msg_from == $profile_id) {
        if (!array_key_exists($row->msg_to, $contacts)) {
            $q = "SELECT user_id, CONCAT(fname, ' ', lname) AS user_name from users where user_id = $row->msg_to";

            if ($result1 = $db->query($q)) {
                $row1 = $result1->fetch_object();
                $contacts[$row->msg_to] = new User($row1->user_id, $row1->user_name);
            }
        }
        unset($messages[$row->msg_to]);  // to maintain ordered list by time
        $messages[$row->msg_to] = new Message($row->msg_id, $row->msg_content, $row->msg_from, $profile_name, $row->msg_to, $contacts[$row->msg_to]->name, $row->msgd_on);
    } else {
        if (!array_key_exists($row->msg_from, $contacts)) {
            $q = "SELECT user_id, CONCAT(fname, ' ', lname) AS user_name from users where user_id = $row->msg_from";

            if ($result1 = $db->query($q)) {
                $row1 = $result1->fetch_object();
                $contacts[$row->msg_from] = new User($row1->user_id, $row1->user_name);
            }
        }
        unset($messages[$row->msg_to]);
        $messages[$row->msg_from] = new Message($row->msg_id, $row->msg_content, $row->msg_from, $contacts[$row->msg_from]->name, $row->msg_to, $profile_name, $row->msgd_on);
    }
}
$messages = array_reverse($messages, true);
?>

<div id="container" class="mx-auto my-3">
    <?php if (empty($messages)) { ?>
        <div class="card p-3 my-2">
            <div class="card-body">
                <span>It looks like you don't have any messages at this moment.</span>
            </div>
        </div>
    <?php } else { ?>
        <div class="card p-3 my-2">
            <div class="card-title font-weight-bold text-center mb-0">
                <?= print_array_count($contacts, "contact") ?>
            </div>
            <hr>

            <div class="card-body">
                <?php foreach ($messages as $key => $message) { ?>
                    <div class="row">
                        <div class="col-8 text-left">
                            <a href="message.php?id=<?= $contacts[$key]->id ?>" ?><?= $contacts[$key]->name ?></a>
                        </div>

                        <div class="col-4 text-right">
                            <?= get_date_for_database($message->time) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <?= $message->content ?>
                        </div>
                    </div>
                    <hr>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php include("./includes/footer.php"); ?>