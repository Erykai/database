<?php

namespace Erykai\Database;

use PDO;
use PDOException;

class Database
{
    private PDO $conn;
    private string $id;
    private string $table;
    private object $data;
    private null|object $stmt = null;
    private null|string $columns;
    private null|string $values;
    private mixed $params;
    private array $notNull;

    protected string $error;

    protected function __construct(string $table, array $notNull, string $id = 'id')
    {
        $this->conn();
        $this->id = $id;
        $this->table = $table;
        $this->notNull = $notNull;
    }

    private function conn(): PDO
    {
        if (empty($this->conn)) {
            try {
                $this->conn = new PDO(
                    CONN_DSN . ":host=" . CONN_HOST . ";dbname=" . CONN_BASE,
                    CONN_USER,
                    CONN_PASS,
                    [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ]
                );
            } catch (PDOException $e) {
                echo $e->getMessage() . " - in file " .
                    $e->getTrace()[1]['file'] . ' in line ' .
                    $e->getTrace()[1]['line'] . ' in function ' .
                    $e->getTrace()[1]['function'];
            }
        }
        return $this->conn;
    }

    protected function create(object $data)
    {
        if (!$this->notNull($data)) {
            return false;
        }

        $this->columns = null;
        $this->values = null;
        $this->params = null;
        foreach (get_object_vars($data) as $key => $value) {
            $this->columns .= ',' . $key;
            $this->values .= ',:' . $key;
            $this->params[$key] = $value;
        }
        $this->columns = substr($this->columns, 1);
        $this->values = substr($this->values, 1);

        $this->stmt = $this->conn->prepare("INSERT INTO $this->table ($this->columns) VALUES ($this->values)");
        foreach ($this->params as $key => &$param) {
            $this->stmt->bindParam(":$key", $param);
        }

        $this->data = $data;
        return true;
    }

    protected function fullRead()
    {
        $read = $this->conn->query("SELECT * FROM users");
        var_dump($read->fetchAll());

    }

    protected function read()
    {
        $read = $this->conn->query("SELECT * FROM users");
        $read->fetch();

    }

    protected function update()
    {
        $stmt = $this->conn->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        $stmt->execute();
    }

    protected function delete(object $data): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $id = $data->id;
        return $stmt->execute();
    }

    protected function data()
    {
        return $this->data;
    }

    protected function error()
    {
        if(!empty($this->error)){
            return true;
        }
        return false;
    }

    protected function notNull(object $data): bool
    {
        $data = get_object_vars($data);
        foreach ($this->notNull as $key) {
            if (!array_key_exists($key, $data) || empty($data[$key])) {
                $this->error = "the $key is mandatory, it cannot be null or empty";
                return false;
            }
        }
        return true;
    }
    protected function execute()
    {
        if($this->stmt->execute())
        {
            if($this->conn->lastInsertId()){
                $this->data->id = $this->conn->lastInsertId();
            }
            return true;
        }
        return false;
    }

}