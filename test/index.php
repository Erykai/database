<?php
use Erykai\Database\Users;

require "vendor/autoload.php";
const CONN_USER = 'root';
const CONN_PASS = 'root';
const CONN_BASE = 'erykai';
const CONN_HOST = 'mysql';
const CONN_DSN = 'mysql';

$Users = new Users();

$user = new stdClass();
$user->name = "Teste";
$user->email = "teste@webav.com.br";
$user->password = "102asda030";
$user->age = 90;

$Users->store($user);