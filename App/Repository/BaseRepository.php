<?php

namespace App\Repository;

use PDO;
use PDOStatement;
use App\Contract\BaseRepositoryInterface;

abstract class BaseRepository
implements BaseRepositoryInterface
{
    protected PDO $db;

    protected string $table;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /*
    |--------------------------------------------------------------------------
    | GET BY ID
    |--------------------------------------------------------------------------
    */

    public function getById(
        int $id
    ): mixed {

        $stmt = $this->query(
            "
            SELECT *
            FROM {$this->table}
            WHERE id = :id
            LIMIT 1
            ",
            [
                'id' => $id
            ]
        );

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL
    |--------------------------------------------------------------------------
    */

    public function getAll(
        ?int $limit = null,
        int $offset = 0
    ): array {

        $sql = "
            SELECT *
            FROM {$this->table}
        ";

        if ($limit !== null) {

            $sql .= "
                LIMIT {$limit}
                OFFSET {$offset}
            ";
        }

        $stmt = $this->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | COUNT
    |--------------------------------------------------------------------------
    */

    public function count(): int
    {
        $stmt = $this->query(
            "
            SELECT COUNT(*)
            FROM {$this->table}
            "
        );

        return (int) $stmt->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | EXECUTE STORED PROCEDURE
    |--------------------------------------------------------------------------
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

            $stmt->bindValue(
                $index + 1,
                $value,
                $type
            );
        }

        $stmt->execute();

        return $stmt;
    }

    /*
    |--------------------------------------------------------------------------
    | FETCH ALL PROCEDURE
    |--------------------------------------------------------------------------
    */

    protected function fetchAllProcedure(
        string $procedure,
        array $params = []
    ): array {

        $stmt = $this->callProcedure(
            $procedure,
            $params
        );

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | FETCH SINGLE PROCEDURE
    |--------------------------------------------------------------------------
    */

    protected function fetchProcedure(
        string $procedure,
        array $params = []
    ): ?array {

        $stmt = $this->callProcedure(
            $procedure,
            $params
        );

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        return $data ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | EXECUTE QUERY
    |--------------------------------------------------------------------------
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