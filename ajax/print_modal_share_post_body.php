<?php
require_once(__DIR__ . "/../utilities/connect_to_db.php");
require_once(__DIR__ . "/../classes/post.class.php");
require_once(__DIR__ . "/../includes/fetch_post_by_id.php");
require_once(__DIR__ . "/../includes/generic_functions.php");

if ($_POST["id"]) {
    $id = (int) $_POST["id"];
    $post = post_by_id($db, $id);
    echo load_modal_share_post_body($post);
}
