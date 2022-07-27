<?php

namespace Erykai\Database;

class Database extends Resource
{
    use TraitDatabase;

    public function __construct(string $table, array $notNull, string $id = 'id')
    {
        $this->conn();
        $this->id = $id;
        $this->table = $table;
        $this->notNull = $notNull;
    }

    protected function create(object $data): bool
    {
        if (!$this->notNull($data)) {
            return false;
        }
        $this->params = get_object_vars($data);

        foreach ($this->params as $key => $param) {
            $this->columns .= ',' . $key;
            $this->values .= ',:' . $key;
        }

        $this->columns = substr($this->columns, 1);
        $this->values = substr($this->values, 1);
        $this->query = "INSERT INTO $this->table ($this->columns) VALUES ($this->values)";
        $this->stmt = $this->conn->prepare($this->query);
        $this->bind($this->params);

        $this->data = $data;
        return true;
    }



    protected function find(string $columns = '*', string $condition = null, array $params = [])
    {
        $this->query = "SELECT $columns FROM $this->table";
        $this->params = $params;
        if ($condition) {
            $this->query .= " WHERE $condition ";
        }

        return $this;
    }

    protected function inner(string $inner)
    {
        $this->query .= " $inner ";
        return $this;
    }

    protected function order(string $column, string $order = 'ASC')
    {
        $this->query .= " ORDER BY $column $order ";
        return $this;
    }

    protected function limit(int $limit)
    {
        $this->query .= " LIMIT $limit ";
        return $this;
    }

    protected function offset(int $offset)
    {
        $this->query .= " OFFSET $offset ";
        return $this;
    }

    protected function fetch(bool $all = false)
    {
        $this->stmt = $this->conn->prepare($this->query);
        $this->bind($this->params);
        $this->send();

        if ($all) {
            return $this->stmt->fetchAll();
        }
        return $this->stmt->fetch();
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

    protected function getData(): object
    {
        return $this->data;
    }


    protected function send(): bool
    {
        if ($this->stmt->execute()) {
            if ($this->conn->lastInsertId()) {
                $this->data->id = $this->conn->lastInsertId();
            }
            return true;
        }
        return false;
    }

}