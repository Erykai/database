<?php
use Erykai\Database\Users;
require "test/config.php";
require "vendor/autoload.php";

$user = new Users();
$user->name = "Alex de Oliveira Vidal";
$user->email = "teste@webav.com.br";
$user->password = "102asda030";
$user->age = 10;
if(!$user->save()){
    echo $user->error();
}else{
    $user = $user->data();
    echo "O Cadastro do $user->name foi feito com sucesso!";
}