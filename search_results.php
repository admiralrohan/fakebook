<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (! isset($_SESSION['user_id'])) {
    header("Location: index.php?from=none");
    exit();
}

$users = array();

require_once("./utilities/connect_to_db.php");
include("./classes/friend.class.php");
include("./includes/header.php");
include("./includes/nav.php");

$error_msg = "No profile found with your search result.";

// Check if any search term is specified
if (! isset($_GET["search_term"])) {
    header("Location: profile.php");
    exit();
} else {
    $search_term = filter_var($_GET["search_term"], FILTER_SANITIZE_STRING);

    if (strlen($search_term)) {
        $q = "SELECT user_id, CONCAT(fname, ' ', lname) as user_name from users where fname LIKE '%{$search_term}%' || lname LIKE '%{$search_term}%'";
        $result = $db->query($q);
        echo $db->error;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $users[] = new Friend($row->user_id, $row->user_name);
            }
        }
    } else {
        $error_msg = "Specify the search term.";
    }
}

$title = "Search Results";
?>

<div class="w-50 my-3 vertical-center">
    <div class="text-center font-weight-bold mb-2">Search Results for "<?= $search_term ?>"</div>

    <?php if (empty($users)) { ?>
        <div class="card p-3 my-2">
            <?= $error_msg ?>
        </div>
    <?php } else { ?>
        <div class="card p-3 my-2">
            <div class="card-title font-weight-bold text-center"><?= count($users) ?> Profiles Found</div>
            <hr>

            <div class="card-body">
                <?php foreach ($users as $user) { ?>
                    <div class="card-text row">
                        <div class="col-sm-8 text-left">
                            <a href="profile.php?id=<?= $user->id ?>" ?><?= $user->name ?></a>
                            <!-- <span>1 mutual friend</span> -->
                        </div>

                        <div class="col-sm-4 text-right">
                            <?php
                            $own_id = $_SESSION["user_id"];
                            $profile_id = $user->id;

                            include("./includes/friendship_status.php");
                            ?>
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