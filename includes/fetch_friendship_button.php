<?php
require_once(__DIR__ . "/generic_functions.php");

function fetch_friendship_button(int $profile_id, int $friendship_status_code): string
{
    $button = "";

    switch ($friendship_status_code) {
        case 1:
            $button = load_button_add_friend($profile_id, $friendship_status_code);
            break;
        case 2:
            $button = load_button_friend($profile_id, $friendship_status_code);
            break;
        case 3:
            $button = load_button_friend_request_sent($profile_id, $friendship_status_code);
            break;
        case 4:
            $button = load_button_respond_to_friend_request($profile_id, $friendship_status_code);
            break;

        default:
            break;
    }

    return $button;
}
