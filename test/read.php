<?php

use Erykai\Database\Users;
require "test/config.php";
require "vendor/autoload.php";

$Users = new Users();
//RETORNA TUDO
/*
$users = $Users->find()->fetch(true);
foreach ($users as $user) {
    echo "O $user->name existe!</br>";
}
*/
//RETORNA O PRIMEIRO RESULTADO ENCONTRADO
/*
$user = $Users->find()->fetch();
echo "O $user->name existe!</br>";
*/
//RETORNA O PRIMEIRO RESULTADO DA QUERY
/*
$user = $Users->find('name, email', 'name=:name', ['name'=>'Leonardo'])->fetch();
echo "O $user->name existe!</br>";
*/
//RETORNA TODOS RESULTADOS DA QUERY
/*
$users = $Users->find('name, email', 'name=:name', ['name'=>'Leonardo'])->fetch(true);
foreach ($users as $user) {
    echo "O $user->name existe!</br>";
}
*/
//RETORNA TODOS RESULTADOS DA QUERY POR ORDEM
/*
$users = $Users
    ->find('name, email', 'name=:name', ['name'=>'Leonardo'])
    ->order("email", "DESC")
    ->fetch(true);
foreach ($users as $user) {
    echo "O $user->name existe!</br>";
}
*/
//RETORNA LIMITE DE RESULTADOS DA QUERY
/*
$users = $Users
    ->find('name, email', 'name=:name', ['name'=>'Leonardo'])
    ->limit(2)
    ->fetch(true);
foreach ($users as $user) {
    echo "O $user->name existe!</br>";
}
*/
//RETORNA LIMITE DE RESULTADOS DA QUERY USANDO OFFSET
/*
$users = $Users
    ->find('name, email', 'name=:name', ['name'=>'Leonardo'])
    ->limit(2)
    ->offset(2)
    ->fetch(true);
foreach ($users as $user) {
    echo "O $user->name existe!</br>";
}
*/
//RETORNA RESULTADOS DA QUERY AGRUPADOS
/*
$users = $Users
    ->find('name, email', 'name=:name', ['name'=>'Leonardo'])
    ->group('name')
    ->fetch(true);
foreach ($users as $user) {
    echo "O $user->name existe!</br>";
}
*/
//RETORNA RESULTADOS DE MAIS DE UMA TABELA
/*
$users = $Users
    ->find('name, email, address', 'name=:name', ['name'=>'Leonardo'])
    ->inner('INNER JOIN address ON id_user = users.id')
->fetch(true);
foreach ($users as $user) {
    echo "O $user->name existe e mora reside no endereço: $user->address!</br>";
}
*/