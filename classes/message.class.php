<?php
class Message {
    public $id;
    public $content;
    public $from_id;
    public $from_name;
    public $to_id;
    public $to_name;
    public $msgd_on;

    function __construct($msg_id, $msg_content, $msg_from_id, $msg_from_name, $msg_to_id, $msg_to_name, $msgd_on) {
        $this->id = $msg_id;
        $this->content = $msg_content;
        $this->from_id = $msg_from_id;
        $this->from_name = $msg_from_name;
        $this->to_id = $msg_to_id;
        $this->to_name = $msg_to_name;
        $this->msgd_on = $msgd_on;
    }
}