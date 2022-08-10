<?php
use Erykai\Database\Users;
require "test/config.php";
require "vendor/autoload.php";

$user = new Users();
$user->find('id, name', 'id=:id', ['id'=>6])->fetch();
$data = $user->data();
$user->delete($data->id);
var_dump($user->response());