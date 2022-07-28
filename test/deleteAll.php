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
    if(!$data){
        echo $user->error();
    }else{
        if(!$user->delete($data->id))
        {
            echo $user->error();
        }else{
            echo "O Cadastro do $data->name foi removido com sucesso";
        }
    }
}