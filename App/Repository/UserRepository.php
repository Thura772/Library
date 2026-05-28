<?php

namespace App\Repository;

use App\Model\User;
use PDO;

class UserRepository extends BaseRepository
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->query(
            "SELECT * FROM users WHERE email = :email",
            ['email' => $email]
        );

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? User::fromDatabase($data) : null;
    }

    public function create(User $user): bool
    {
        $stmt = $this->query(
            "INSERT INTO users (name,email,password,role)
             VALUES (:name,:email,:password,:role)",
            [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPasswordHash(),
                'role' => $user->getRole()
            ]
        );

        return $stmt->rowCount() > 0;
    }
}