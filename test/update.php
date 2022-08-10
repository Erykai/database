<?php
use Erykai\Database\Users;
require "test/config.php";
require "vendor/autoload.php";

$user = new Users();
$email = "absurtds@leite.com";
$user->find('*', 'email=:email',['email'=>$email])->fetch();
$users = $user->data();
$users->email = "banana@baasdasdn.cm";
$user->save();
var_dump($user->response());
