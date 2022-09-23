<?php

namespace Erykai\Database;

use PDO;

/**
 * CLASS RESOURCE DATABASE
 */
abstract class Resource
{
    use TraitDatabase;
    /**
     * @var PDO
     */
    protected PDO $conn;
    /**
     * @var string
     */
    protected string $nameId;
    /**
     * @var string
     */
    protected string $table;
    /**
     * @var string
     */
    protected string $query;
    /**
     * @var string
     */
    protected string $queryEnd = "";
    /**
     * @var object|null
     */
    protected null|object $data = null;
    /**
     * @var object|null
     */
    protected null|object $stmt = null;
    /**
     * @var string|null
     */
    protected null|string $columns = null;
    /**
     * @var string|null
     */
    protected null|string $values = null;
    /**
     * @var ?array
     */
    protected ?array $params;
    /**
     * @var array
     */
    protected array $notNull;
    /**
     * @var object
     */
    private object $response;
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
     * @return $this|null
     */
    protected function create(): ?Resource
    {
        if (!$this->notNull($this->data)) {
            return null;
        }
        if (isset($this->data->scalar)) {
            unset($this->data->scalar);
        }

        $this->params = get_object_vars($this->data);
        foreach ($this->params as $key => $param) {
            $this->columns .= ',' . $key;
            $this->values .= ',:' . $key;
        }

        $this->columns = substr($this->columns, 1);
        $this->values = substr($this->values, 1);
        $this->query = "INSERT INTO $this->table ($this->columns) VALUES ($this->values)";
        $this->stmt = $this->conn->prepare($this->query);
        $this->bind($this->params);

        return $this;
    }

    /**
     * @return bool|$this
     */
    protected function update(): bool|static
    {
        $id = $this->nameId;
        if(!isset($this->data->$id)){
            $this->setResponse(400, "error","there is no $id field in the table", dynamic: $id);
            return false;
        }
        $this->setColumns($this->data);
        $this->setQuery();
        return $this;

    }

    /**
     * @param array $params
     */
    protected function bind(array $params): void
    {
        foreach ($params as $key => &$param) {
            $this->stmt->bindParam(":$key", $param);
        }
    }

    /**
     * @param object $data
     * @return bool
     */
    protected function notNull(object $data): bool
    {
        $data = get_object_vars($data);
        foreach ($this->notNull as $key) {
            if (!array_key_exists($key, $data) || empty($data[$key])) {
                $this->setResponse(400,'error',"the $key is mandatory, it cannot be null or empty", dynamic: $key);
                return false;
            }
        }
        return true;
    }

    /**
     * @param object $data
     */
    protected function setColumns(object $data): void
    {
        $this->params = get_object_vars($data);
        foreach ($this->params as $key => $param) {
            $this->columns .= "`$key`=:$key,";
        }
        $this->columns = substr($this->columns, 0, -1);
    }

    /**
     *
     */
    protected function setQuery(): void
    {
        $id = $this->nameId;
        $this->query = "UPDATE $this->table SET  $this->columns WHERE $id = :$id";
        $this->stmt = $this->conn->prepare($this->query);
        $this->bind($this->params);
    }

    /**
     * @return object
     */
    protected function getResponse(): object
    {
        return $this->response;
    }

    /**
     * @param int $code
     * @param string $type
     * @param string $text
     * @param string $model
     * @param object|null $data
     * @param string|null $dynamic
     */
    protected function setResponse(int $code, string $type, string $text, string $model, ?object $data = null, ?string $dynamic = null): void
    {
        $this->response = (object)[
            "code" => $code,
            "type" => $type,
            "text" => $text,
            "model" => $model,
            "data" => $data,
            "dynamic" => $dynamic
        ];
    }
}