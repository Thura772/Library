<?php

namespace App\Repository;

use PDO;
use App\Contract\BaseRepositoryInterface;


abstract class BaseRepository implements BaseRepositoryInterface
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    public function count(array $filters = []): int
    {
        $category = $filters['category'] ?? null;
        $search = $filters['search'] ?? null;

        $stmt = $this->db->prepare("CALL sp_search_catalog_count(:search, :category)");

        $stmt->bindValue(':search', $search ?: null, $search ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(':category', $category ?: null, $category ? PDO::PARAM_STR : PDO::PARAM_NULL);

        $stmt->execute();

        $count = (int)$stmt->fetchColumn();

        $stmt->nextRowset();
        $stmt->closeCursor();

        return $count;
    }

    /*
     * GET ALL (BaseRepositoryInterface)
     */
    public function getAll($limit = null, $offset = 0)
    {
        $result = $this->db->prepare(" CALL sp_get_full_catalog ( ? , ? )");

        $result->bindParam(
            1,
            $limit,
            $limit === null ? PDO::PARAM_NULL : PDO::PARAM_INT
        );

        $result->bindParam(2, $offset, PDO::PARAM_INT);

        $result->execute();

        $catalog = $result->fetchAll();

        $result->closeCursor();

        return $catalog;
    }

    /*
     * GET BY ID (BaseRepositoryInterface)
     */
    public function getById($id)
    {
        $result = $this->db->prepare("CALL sp_get_item_full_detail (?)");

        $result->bindParam(1, $id, PDO::PARAM_INT);

        $result->execute();

        $item = $result->fetch(PDO::FETCH_ASSOC);

        // Return null if item does not exist
        if ($item === false) {
            $result->closeCursor();
            return null;
        }

        $result->nextRowset();

        // Load related people data (actors, authors, etc.)
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item[strtolower($row['role'])][] = $row['fullname'];
        }

        $result->closeCursor();

        return $item;
    }
}
