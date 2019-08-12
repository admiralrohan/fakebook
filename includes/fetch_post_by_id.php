<?php
function post_by_id(mysqli $db, int $post_id): Post
{
    $query = "SELECT post_id, post_content, u.user_id AS post_owner_id, p.original_post as original_post_id, CONCAT(fname, ' ', lname) AS post_owner_name, posted_on, p.is_shared_post ";
    $query .= "FROM posts AS p INNER JOIN users AS u ";
    $query .= "ON p.post_owner = u.user_id ";
    $query .= "WHERE p.post_id = '$post_id' ";

    $result = $db->query($query);
    if ($result->num_rows == 1) {
        $row = $result->fetch_object();

        if (!$row->is_shared_post) {
            $post = new Post(
                $row->post_id,
                $row->post_content,
                $row->post_owner_id,
                $row->post_owner_name,
                $row->posted_on
            );
        } else {
            $id = (int) $row->original_post_id;

            // Original post has not been deleted
            if ($id) {
                $query = "SELECT post_id, post_content, u.user_id AS post_owner_id, p.original_post as original_post_id, CONCAT(fname, ' ', lname) AS post_owner_name, posted_on, p.is_shared_post ";
                $query .= "FROM posts AS p INNER JOIN users AS u ";
                $query .= "ON p.post_owner = u.user_id ";
                $query .= "WHERE p.post_id = '$id' ";

                $result = $db->query($query);
                if ($result->num_rows == 1) {
                    $new_row = $result->fetch_object();

                    if (!$new_row->is_shared_post) {
                        $post = new Post(
                            $row->post_id,
                            $row->post_content,
                            $row->post_owner_id,
                            $row->post_owner_name,
                            $row->posted_on,
                            new Post(
                                $new_row->post_id,
                                $new_row->post_content,
                                $new_row->post_owner_id,
                                $new_row->post_owner_name,
                                $new_row->posted_on
                            ),
                            $row->is_shared_post
                        );
                    } else {
                        header("Location: page_not_found.php");
                        exit();
                    }
                } else {
                    header("Location: page_not_found.php");
                    exit();
                }
            } else { // Original post has been deleted
                $post = new Post(
                    $row->post_id,
                    $row->post_content,
                    $row->post_owner_id,
                    $row->post_owner_name,
                    $row->posted_on,
                    $row->original_post_id,
                    $row->is_shared_post
                );
            }
        }
    } else {
        header("Location: page_not_found.php");
        exit();
    }

    return $post;
}
