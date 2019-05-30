<?php
class Post {
    public $post_id;
    public $post_content;
    public $post_owner_id;
    public $post_owner_name;
    public $posted_on;

    function __construct($id, $content, $owner_id, $owner_name, $time) {
        $this->post_id = $id;
        $this->post_content = $content;
        $this->post_owner_id = $owner_id;
        $this->post_owner_name = $owner_name;
        $this->posted_on = $time;
    }
}