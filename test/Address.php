<?php

namespace Erykai\Database;

class Address extends Database
{
    public function __construct()
    {
        parent::__construct(
            'address',
            ['address'],
            'id'
        );
    }
}