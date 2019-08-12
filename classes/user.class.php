<?php
class User
{
    public $id;
    public $name;
    public $friendshipStatus;

    function __construct(
        int $id,
        string $name,
        int $friendshipStatus = 0
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->friendshipStatus = $friendshipStatus;
    }
}
