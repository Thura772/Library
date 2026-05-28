<?php

namespace App\Model;

class User
{
    private function __construct(
        private ?int $id,
        private string $name,
        private string $email,
        private string $passwordHash,
        private string $role
    ) {}

    public static function fromDatabase(array $data): self
    {
        return new self(
            $data['id'] ? (int)$data['id'] : null,
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role']
        );
    }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getRole() { return $this->role; }
    public function getPasswordHash() { return $this->passwordHash; }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }
}