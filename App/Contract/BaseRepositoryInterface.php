<?php

namespace App\Contract;
/*
 Base interface for all repository classes
 Provides common data access methods
*/

interface BaseRepositoryInterface
{
    /*
     Get single record by ID
    */
    public function getById(int $id);

    /*
     Get all records
    */
    public function getAll(
        ?int $limit = null,
        int $offset = 0
    );

    /*
     Get total record count
    */
    public function count(array $filters = []): int;
}