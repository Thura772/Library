<?php

namespace App\Repository;

use PDO;
use App\Contract\CatalogRepositoryInterface;

class CatalogRepository extends BaseRepository
implements CatalogRepositoryInterface
{
    /*
     * GET ALL
     */
    public function getAll(
        ?int $limit = null,
        int $offset = 0
    ): array {

        return $this->fetchAllProcedure(
            'sp_get_full_catalog',
            [$limit, $offset]
        );
    }

    /*
     * GET BY ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->callProcedure(
            'sp_get_item_full_detail',
            [$id]
        );

        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            $stmt->closeCursor();
            return null;
        }

        $stmt->nextRowset();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $item[strtolower($row['role'])][] =
                $row['fullname'];
        }

        $stmt->closeCursor();

        return $item;
    }

    /*
     * COUNT
     */
    public function count(array $filters = []): int
    {
        $stmt = $this->callProcedure(
            'sp_search_catalog_count',
            [
                $filters['search'] ?? null,
                $filters['category'] ?? null
            ]
        );

        $count = (int) $stmt->fetchColumn();

        $stmt->closeCursor();

        return $count;
    }

    /*
     * GET BY CATEGORY
     */
    public function getByCategory(
        string $category,
        ?int $limit = null,
        int $offset = 0
    ): array {

        return $this->fetchAllProcedure(
            'sp_get_catalog',
            [$category, $limit, $offset]
        );
    }

    /*
     * SEARCH
     */
    public function search(
        string $keyword,
        ?string $category = null,
        ?int $limit = null,
        int $offset = 0
    ): array {

        $keyword = $keyword ?: null;
        $category = $category ?: null;

        return $this->fetchAllProcedure(
            'sp_search_catalog',
            [
                $keyword,
                $category,
                $limit,
                $offset
            ]
        );
    }

    /*
     * RANDOM
     */
    public function getRandom(): array
    {
        $stmt = $this->query(
            "SELECT * FROM view_random"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}