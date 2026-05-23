<?php

// use BaseRepository;
// File: Repository/UserRepository.php

require_once BASE_PATH . '/Repository/BaseRepository.php';

class UserRepository extends BaseRepository 
{
    /**
     * Check if an email already exists in the system
     */
    public function findByEmail(string $email): ?array 
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        
        return $user ? $user : null;
    }

    /**
     * Insert a new user into the database
     */
    public function create(array $data): bool 
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password) 
            VALUES (:name, :email, :password)
        ");
        
        return $stmt->execute([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password']
        ]);
    }
}