<?php
use Erykai\Database\Users;
require "test/config.php";
require "vendor/autoload.php";

$user = new Users();
$user->name = "Alex de Oliveira Vidal";
$user->email = "teste@webav.com.br";
$user->password = "102asda030";
$user->age = 10;
$user->save();
var_dump($user->response(), $user->data());
