<?php

namespace Erykai\Database;

class Users extends Database
{
    public function __construct()
    {
        parent::__construct(
            'users',
            ['name', 'email', 'age'],
            'id'
        );
    }

    public function store($data)
    {
        $this->create($data);

        if(!$this->error()){
            $this->execute();
            var_dump($this->data());
            return true;
        }
        var_dump($this->error);
        return false;
    }

    public function find()
    {
        return $this->read();
    }

    public function findAll()
    {
        return $this->fullRead();
    }

    public function edit()
    {
        return $this->update();
    }

    public function remove()
    {
        return $this->delete();
    }


}