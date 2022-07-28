<?php
use Erykai\Database\Users;
require "test/config.php";
require "vendor/autoload.php";

$user = new Users();
$user->find('id, name', 'id=:id', ['id'=>6])->fetch();
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