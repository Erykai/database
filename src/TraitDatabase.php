<?php

namespace Erykai\Database;
use PDO;
use PDOException;
trait TraitDatabase
{
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
}