<?php
include(__DIR__ . "/../includes/generic_functions.php");

if ($_POST["likedUsers"]) {
    echo load_modal_liked_users_body($_POST["likedUsers"]);
}
