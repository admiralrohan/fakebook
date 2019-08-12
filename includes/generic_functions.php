<?php

/**
 * Takes an array and print the number of elements in useful manner in the view.
 * e.g No like yet / 1 like / 3 likes
 *
 * @param array $arr The array to count
 * @param string $text_in_middle Text to put in the middle
 * @param boolean $use_yet Whether append "yet" at the end of the string
 * @return string
 */
function print_array_count(array $arr, string $text_in_middle, bool $use_yet = true): string
{
    return (count($arr) === 0 ? 'No' : count($arr)) . " {$text_in_middle}" . (count($arr) > 1 ? 's' : '') . ($use_yet ? (count($arr) === 0 ? ' yet' : '') : '');
}

function get_date_for_database(string $date): string
{
    $timestamp = strtotime($date);
    $date_formated = date('Y-m-d H:i:s', $timestamp);
    return $date_formated;
}

function load_post($db, $post, $own_id)
{
    ob_start();
    require(__DIR__ . "/../views/load_post.php");
    return ob_get_clean();
}

function load_comment($db, $comment, $own_id)
{
    ob_start();
    require(__DIR__ . "/../views/load_comment.php");
    return ob_get_clean();
}

function load_comment_without_like($comment, $own_id)
{
    ob_start();
    require(__DIR__ . "/../views/load_comment_without_like.php");
    return ob_get_clean();
}

function load_button_add_friend($profile_id, $friendship_status_code)
{
    ob_start();
    require(__DIR__ . "/../views/load_button_add_friend.php");
    return ob_get_clean();
}

function load_button_friend($profile_id, $friendship_status_code)
{
    ob_start();
    require(__DIR__ . "/../views/load_button_friend.php");
    return ob_get_clean();
}

function load_button_friend_request_sent($profile_id, $friendship_status_code)
{
    ob_start();
    require(__DIR__ . "/../views/load_button_friend_request_sent.php");
    return ob_get_clean();
}

function load_button_respond_to_friend_request($profile_id, $friendship_status_code)
{
    ob_start();
    require(__DIR__ . "/../views/load_button_respond_to_friend_request.php");
    return ob_get_clean();
}

function load_class_like_count($post_liked_by_users)
{
    ob_start();
    require(__DIR__ . "/../views/load_class_like_count.php");
    return ob_get_clean();
}

function load_button_like_post($is_post_liked_by_user)
{
    ob_start();
    require(__DIR__ . "/../views/load_button_like_post.php");
    return ob_get_clean();
}

function load_modal_liked_users_header($post_liked_by_users)
{
    ob_start();
    require(__DIR__ . "/../views/load_modal_liked_users_header.php");
    return ob_get_clean();
}

function load_modal_liked_users_body($post_liked_by_users)
{
    ob_start();
    require(__DIR__ . "/../views/load_modal_liked_users_body.php");
    return ob_get_clean();
}

function load_modal_share_post_body($post)
{
    ob_start();
    require(__DIR__ . "/../views/load_modal_share_post_body.php");
    return ob_get_clean();
}
