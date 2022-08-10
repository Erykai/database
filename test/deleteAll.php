<?php
use Erykai\Database\Users;
require "test/config.php";
require "vendor/autoload.php";

$removeAll = new Users();
$removeAlls = $removeAll->find('id', 'email=:email',['email'=> 'id2@leite.com'])->fetch(true);
foreach ($removeAll->data() as $userDel) {
    $user = new Users();
    $user->find('id, name', 'id=:id', ['id'=>$userDel->id])->fetch();
    $data = $user->data();
    $user->delete($data->id);
    var_dump($user->response());
}