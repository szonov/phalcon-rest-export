<?php

namespace PhalconRestExport\Postman;

class Folder
{
    public $id;
    public $name;
    public $description;
    public $order = [];

    public function __construct($id, $name, $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function addRequest($requestId)
    {
        $this->order[] = $requestId;
        return $this;
    }
}
