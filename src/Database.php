<?php

namespace Erykai\Database;

use PDO;
use stdClass;

/**
 * CLASS CRUD
 */
class Database extends Resource
{
    use TraitDatabase;

    /**
     * @param string $table
     * @param array $notNull
     * @param string $id
     */
    public function __construct(string $table, array $notNull, string $id = 'id')
    {
        $this->conn();
        $this->nameId = $id;
        $this->table = $table;
        $this->notNull = $notNull;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (empty($this->data)) {
            $this->data = new stdClass();
        }
        if($name !== 'updated_at' && $name !== 'created_at'){
            $this->data->$name = $value;
        }

    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->data->$name;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    /**
     * @param string $columns
     * @param string|null $condition
     * @param array $params
     * @return $this
     */
    public function find(string $columns = '*', string $condition = null, array $params = []): static
    {
        $this->query = "SELECT $columns FROM $this->table";
        $this->params = $params;
        if ($condition) {
            $this->query .= " WHERE $condition ";
        }

        return $this;
    }

    /**
     * @param string $inner
     * @return $this
     */
    public function inner(string $inner): static
    {
        if (str_contains($this->query, 'WHERE')) {
           $query  = explode("WHERE", $this->query);
           $this->query = $query[0];
        }
        $this->query .= " $inner ";
        if (isset($query[1])){
            $this->query .= "WHERE $query[1] ";
        }
        return $this;
    }

    /**
     * @param string $column
     * @param string $order
     * @return $this
     */
    public function order(string $column, string $order = 'ASC'): static
    {
        $this->query .= " ORDER BY $column $order ";
        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function group(string $column): static
    {
        $this->query .= " GROUP BY $column ";
        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): static
    {
        $this->query .= " LIMIT $limit ";
        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset): static
    {
        $this->query .= " OFFSET $offset ";
        return $this;
    }

    /**
     * @param bool $all
     * @return object|null
     */
    public function fetch(bool $all = false): ?object
    {
        $this->data = null;
        $this->stmt = $this->conn->prepare($this->query);
        $this->bind($this->params);
        $this->stmt->execute();

        if ($all) {
            if ($this->stmt->rowCount()) {
                $this->data = (object)$this->stmt->fetchAll();
            } else {
                $this->error = 'no results found';
            }
        } else if ($this->stmt->rowCount()) {
            $this->data = (object)$this->stmt->fetch();
        } else {
            $this->error = 'no results found';
        }

        return $this->data;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {

        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE $this->nameId = :$this->nameId");
        $stmt->bindParam(":$this->nameId", $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }
        $this->error = 'failed to delete';
        return false;
    }

    /**
     * @param string $columns
     * @return object|null
     */
    public function data(string $columns = "*"): ?object
    {
        if (!empty($this->data->{0})) {
            return $this->data;
        }

        $id = $this->nameId;
        if (!isset($this->data->$id)) {
            $this->find($columns, "$id=:$id", [$id => $this->conn->lastInsertId()])->fetch();
            return $this->data;
        }
        $this->find($columns, "$id=:$id", [$id => $this->data->$id])->fetch();
        return $this->data;
    }

    /**
     * @return string|bool
     */
    public function error(): string|bool
    {
        if (!empty($this->error)) {
            return $this->error;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        $id = $this->nameId;
        if (!isset($this->data->$id)) {
            if ($this->create()) {
                $this->stmt->execute();
                return true;
            }
            return false;
        }

        if ($this->update()) {
            $this->stmt->execute();
            return true;
        }

        return false;
    }

}