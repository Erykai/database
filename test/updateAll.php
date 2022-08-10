<?php
use Erykai\Database\Users;
require "test/config.php";
require "vendor/autoload.php";

$updateAll = new Users();
$updateAlls = $updateAll->find('email', 'email=:email', ['email'=>'banana@baasdasdn.cm'])->fetch(true);

foreach ($updateAll->data() as $userUpdate) {
    $user = new Users();
    $email = $userUpdate->email;
    $user->find('*', 'email=:email',['email'=>$email])->fetch();
    $users = $user->data();
    $users->email = "asdasdasd@asdasdasd.com";
    $user->save();
    var_dump($user->response());
}