<?php

namespace App\Repository;

use PDO;
use PDOStatement;

abstract class BaseRepository
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /*
     * EXECUTE STORED PROCEDURE
     */
    protected function callProcedure(
        string $procedure,
        array $params = []
    ): PDOStatement {

        $placeholders = '';

        if (!empty($params)) {
            $placeholders = implode(
                ', ',
                array_fill(0, count($params), '?')
            );
        }

        $sql = "CALL {$procedure}({$placeholders})";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $index => $value) {

            $type = match (true) {
                is_int($value)  => PDO::PARAM_INT,
                is_null($value) => PDO::PARAM_NULL,
                default         => PDO::PARAM_STR
            };

            $stmt->bindValue($index + 1, $value, $type);
        }

        $stmt->execute();

        return $stmt;
    }

    /*
     * FETCH ALL
     */
    protected function fetchAllProcedure(
        string $procedure,
        array $params = []
    ): array {

        $stmt = $this->callProcedure($procedure, $params);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        return $data;
    }

    /*
     * FETCH SINGLE ROW
     */
    protected function fetchProcedure(
        string $procedure,
        array $params = []
    ): ?array {

        $stmt = $this->callProcedure($procedure, $params);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        return $data ?: null;
    }

    /*
     * EXECUTE NORMAL QUERY
     */
    protected function query(
        string $sql,
        array $params = []
    ): PDOStatement {

        $stmt = $this->db->prepare($sql);

        $stmt->execute($params);

        return $stmt;
    }
}
