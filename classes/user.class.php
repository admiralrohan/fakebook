<?php
class User
{
    public $id;
    public $name;

    function __construct(
        int $id,
        string $name
    ) {
        $this->id = $id;
        $this->name = $name;
    }
}
