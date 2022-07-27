<?php

namespace Erykai\Database;

use PDO;

class Resource
{
    protected PDO $conn;
    protected string $id;
    protected string $table;
    protected string $query;
    protected object $data;
    protected null|object $stmt = null;
    protected null|string $columns = null;
    protected null|string $values = null;
    protected array $params;
    protected array $notNull;
    protected string $error;

    protected function bind(array $params): void
    {
        foreach ($params as $key => $param) {
            $this->stmt->bindParam(":$key", $param);
        }
    }

    protected function getError(): string|bool
    {
        if (!empty($this->error)) {
            return $this->error;
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
}