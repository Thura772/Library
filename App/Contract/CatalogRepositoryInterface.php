<?php

namespace App\Contract;

use App\Contract\BaseRepositoryInterface;

/*
 Repository interface for catalog-related operations
*/

interface CatalogRepositoryInterface extends BaseRepositoryInterface
{
    /*
     Get catalog items by category
    */
    public function getByCategory(
        string $category,
        ?int $limit = null,
        int $offset = 0
    ): array;

    /*
     Search catalog items
    */
    public function search(
        string $keyword,
        ?string $category = null,
        ?int $limit = null,
        int $offset = 0
    ): array;

    /*
     Get random catalog items
    */
    public function getRandom(): array;
}