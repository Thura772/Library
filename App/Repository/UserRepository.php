<?php

namespace App\Repository;

use PDO;
use App\Contract\UserRepositoryInterface;
use App\Model\User;

class UserRepository extends BaseRepository
implements UserRepositoryInterface
{
    /*
     * FIND USER BY EMAIL
     */
    public function findByEmail(
    string $email
): ?User {

    $stmt = $this->query(
        "
        SELECT *
        FROM users
        WHERE email = :email
        LIMIT 1
        ",
        ['email' => $email]
    );

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        return null;
    }

    return new User(
        $data['id'],
        $data['name'],
        $data['email'],
        $data['password'],
        $data['role']
    );
}

    /*
     * CREATE USER
     */
    public function create(User $user): bool
    {
        $stmt = $this->query(
            "
        INSERT INTO users
        (name, email, password, role)
        VALUES
        (:name, :email, :password, :role)
        ",
            [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'role' => $user->getRole()
            ]
        );

        return $stmt->rowCount() > 0;
    }
}