# Database erykai/database
[![Maintainer](http://img.shields.io/badge/maintainer-@alexdeovidal-blue.svg?style=flat-square)](https://instagram.com/alexdeovidal)
[![Source Code](http://img.shields.io/badge/source-erykai/database-blue.svg?style=flat-square)](https://github.com/erykai/database)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/erykai/database.svg?style=flat-square)](https://packagist.org/packages/erykai/database)
[![Latest Version](https://img.shields.io/github/release/erykai/database.svg?style=flat-square)](https://github.com/erykai/database/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Quality Score](https://img.shields.io/scrutinizer/g/erykai/database.svg?style=flat-square)](https://scrutinizer-ci.com/g/erykai/database)
[![Total Downloads](https://img.shields.io/packagist/dt/erykai/database.svg?style=flat-square)](https://packagist.org/packages/erykai/database)

Responsible for making CRUD with database, using PDO, compatible with: Cubrid, FreeTDS / Microsoft SQL Server / Sybase,  Firebird, IBM DB2, IBM Informix Dynamic Server, MySQL 3.x/4.x/5.x, Oracle Call Interface,  ODBC v3 (IBM DB2, unixODBC and win32 ODBC), PostgreSQL, SQLite 3 and SQLite 2, Microsoft SQL Server / SQL Azure e MariaDB


## Installation

Composer:

```bash
"erykai/database": "1.1.*"
```

Terminal

```bash
composer require erykai/database
```

Create Model.php

```php
namespace Erykai\Database;

class Users extends Database
{
    public function __construct()
    {
        parent::__construct(
            'users',
            ['name', 'email', 'age'],
            'id'
        );
    }
}
```

Constants

```php
const CONN_USER = 'root';
const CONN_PASS = 'root';
const CONN_BASE = 'erykai';
const CONN_HOST = 'mysql';
const CONN_DSN = 'mysql';
```

CREATE

```php
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
```

READ

```php
use Erykai\Database\Users;

require "vendor/autoload.php";

$Users = new Users();

//RETURN ALL
$users = $Users->find()->fetch(true);
foreach ($users as $user) {
    echo "O $user->name existe!</br>";
}

//RETURNS THE FIRST RESULT FOUND
$user = $Users->find()->fetch();
echo "O $user->name existe!</br>";

//RETURNS THE FIRST RESULT OF THE QUERY
$user = $Users->find('name, email', 'name=:name', ['name'=>'Leonardo'])->fetch();
echo "O $user->name existe!</br>";

//RETURNS ALL QUERY RESULTS
$users = $Users->find('name, email', 'name=:name', ['name'=>'Leonardo'])->fetch(true);
foreach ($users as $user) {
    echo "O $user->name existe!</br>";
}

//RETURNS ALL QUERY RESULTS IN ORDER
$users = $Users
    ->find('name, email', 'name=:name', ['name'=>'Leonardo'])
    ->order("email", "DESC")
    ->fetch(true);
foreach ($users as $user) {
    echo "O $user->name existe!</br>";
}

//RETURNS QUERY RESULTS LIMIT
$users = $Users
    ->find('name, email', 'name=:name', ['name'=>'Leonardo'])
    ->limit(2)
    ->fetch(true);
foreach ($users as $user) {
    echo "O $user->name existe!</br>";
}

//RETURNS QUERY RESULTS LIMIT USING OFFSET
$users = $Users
    ->find('name, email', 'name=:name', ['name'=>'Leonardo'])
    ->limit(2)
    ->offset(2)
    ->fetch(true);
foreach ($users as $user) {
    echo "O $user->name existe!</br>";
}

//RETURNS GROUPED QUERY RESULTS
$users = $Users
    ->find('name, email', 'name=:name', ['name'=>'Leonardo'])
    ->group('name')
    ->fetch(true);
foreach ($users as $user) {
    echo "O $user->name existe!</br>";
}

//RETURNS RESULTS FROM MORE THAN ONE TABLE
$users = $Users
    ->find('name, email, address', 'name=:name', ['name'=>'Leonardo'])
    ->inner('INNER JOIN address ON id_user = users.id')
->fetch(true);
foreach ($users as $user) {
    echo "O $user->name existe e mora reside no endereço: $user->address!</br>";
}

//response
var_dump($Users->response());

```
UPDATE

```php
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
```
UPDATE ALL

```php
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
```
DELETE

```php
use Erykai\Database\Users;
require "test/config.php";
require "vendor/autoload.php";

$user = new Users();
$user->find('id, name', 'id=:id', ['id'=>6])->fetch();
$data = $user->data();
$user->delete($data->id);
var_dump($user->response());
```
DELETE ALL

```php
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
```

## Contribution

All contributions will be analyzed, if you make more than one change, make the commit one by one.

## Support


If you find faults send an email reporting to webav.com.br@gmail.com.

## Credits

- [Alex de O. Vidal](https://github.com/alexdeovidal) (Developer)
- [All contributions](https://github.com/erykai/database/contributors) (Contributors)

## License

The MIT License (MIT). Please see [License](https://github.com/erykai/database/LICENSE) for more information.
