<?php

namespace App\Repository;

use PDO;
use App\Contract\CatalogRepositoryInterface;
use App\Repository\BaseRepository;

class CatalogRepository extends BaseRepository implements CatalogRepositoryInterface
{
    /*
     * GET BY CATEGORY
     */
    public function getByCategory(string $category, ?int $limit = null, int $offset = 0): array
    {
        $stmt = $this->db->prepare("CALL sp_get_catalog(?, ?, ?)");

        $stmt->bindValue(1, $category, PDO::PARAM_STR);
        $stmt->bindValue(2, $limit, $limit === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetchAll();
        $stmt->closeCursor();

        return $data;
    }


    /*
     * SEARCH
     */
    public function search(string $keyword, ?string $category = null, ?int $limit = null, int $offset = 0): array
    {
        $keyword = $keyword === '' ? null : $keyword;
        $category = $category === '' ? null : $category;

        $stmt = $this->db->prepare("CALL sp_search_catalog(?, ?, ?, ?)");

        $stmt->bindValue(1, $keyword, $keyword ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(2, $category, $category ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(3, $limit, PDO::PARAM_INT);
        $stmt->bindValue(4, $offset, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetchAll();
        $stmt->nextRowset();
        $stmt->closeCursor();

        return $data;
    }

    /*
     * RANDOM
     */
    public function getRandom(): array
    {
        $stmt = $this->db->query("SELECT * FROM view_random");
        return $stmt->fetchAll();
    }
}
