<?php
require_once(__DIR__ . "/user.class.php");

class Message {
    public $id;
    public $content;
    public $from;
    public $to;
    public $time;

    function __construct(
        int $msg_id,
        string $msg_content,
        int $msg_from_id,
        string $msg_from_name,
        int $msg_to_id,
        string $msg_to_name,
        string $msgd_on
        ) {
        $this->id = $msg_id;
        $this->content = $msg_content;
        $this->from = new User($msg_from_id, $msg_from_name);
        $this->to = new User($msg_to_id, $msg_to_name);
        $this->time = $msgd_on;
    }
}