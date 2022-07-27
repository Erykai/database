<?php

namespace Erykai\Database;

class Address extends Database
{
    public function __construct()
    {
        parent::__construct('address', ['address']);
    }

    public function store(object $data)
    {
        $this->create($data);
    }

    public function data()
    {
        return $this->getData();
    }

    public function error()
    {
        return $this->getError();
    }

    public function save(): string|bool
    {
        if ($this->getError()) {
            return false;
        }
        $this->send();
        return true;
    }
}