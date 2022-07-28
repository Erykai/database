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
}