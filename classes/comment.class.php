<?php
require_once(__DIR__ . "/user.class.php");

class Comment {
    public $id;
    public $content;
    public $post_id;
    public $owner;
    public $time;

    function __construct(
        int $id,
        string $content,
        int $post_id,
        int $owner_id,
        string $owner_name,
        string $commented_on
        ) {
        $this->id = $id;
        $this->content = $content;
        $this->post_id = $post_id;
        $this->owner = new User($owner_id, $owner_name);
        $this->time = $commented_on;
    }
}