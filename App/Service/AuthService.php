<?php

namespace App\Service;

use App\Contract\UserRepositoryInterface;
use App\Validation\Validator;
use App\Validation\AuthValidator;
use App\Model\User;

class AuthService extends BaseService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /*
    | REGISTER
    */
    public function register(array $data): array
{
    $errors = Validator::validate(
        $data,
        AuthValidator::registerRules()
    );

    if (!empty($errors)) {
        return [
            'success' => false,
            'errors' => $errors,
            'old' => $data
        ];
    }

    if ($this->userRepository->findByEmail($data['email'])) {
        return [
            'success' => false,
            'errors' => [
                'email' => 'This email is already registered.'
            ],
            'old' => $data
        ];
    }

   $user = new User(
    null,
    $data['name'],
    $data['email'],
    password_hash($data['password'], PASSWORD_BCRYPT),
    'user'
);

$this->userRepository->create($user);

    return [
        'success' => true,
        'errors' => [],
        'old' => []
    ];
}

    /*
    | LOGIN
    */
  public function login(array $data): array
{
    $errors = Validator::validate(
        $data,
        AuthValidator::loginRules()
    );

    if (!empty($errors)) {
        return [
            'errors' => $errors
        ];
    }

    $user = $this->userRepository
        ->findByEmail($data['email']);

    if (
        !$user ||
        !password_verify(
            $data['password'],
            $user->getPassword()
        )
    ) {
        return [
            'errors' => [
                'general' => 'Invalid email or password.'
            ]
        ];
    }

    return [
        'errors' => [],
        'user' => $user
    ];
}

    public function logout(): void
{
    session_start();
    $_SESSION = [];
    session_destroy();
}
}