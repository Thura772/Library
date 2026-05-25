<?php

namespace App\Contract;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail(string $email): ?array;

    public function create(array $data): bool;
}
