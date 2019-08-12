<?php
require_once(__DIR__ . "/user.class.php");

class Post
{
    public $id;
    public $content;
    public $owner;
    public $time;
    public $originalPost;
    public $isSharedPost;

    function __construct(
        int $id,
        string $content,
        int $owner_id,
        string $owner_name,
        string $posted_on,
        Post $originalPost = null,
        bool $isSharedPost = false
    ) {
        $this->id = $id;
        $this->content = $content;
        $this->owner = new User($owner_id, $owner_name);
        $this->time = $posted_on;
        $this->originalPost = $originalPost;
        $this->isSharedPost = $isSharedPost;
    }
}
