<?php

namespace App\Controller;

use App\Service\AuthService;
use App\Http\Request\RegisterRequest;
use App\Http\Request\LoginRequest;
use App\Exceptions\ValidationException;

class AuthController extends BaseController
{
    public function __construct(
        private AuthService $service
    ) {}

    public function showRegisterForm(): void
    {
        $this->view('auth/register', [
            'pageTitle' => 'Create Account',
            'errors' => [],
            'old' => []
        ]);
    }

    public function register(): void
    {
        $request = new RegisterRequest([
            'name' => $this->post('name', ''),
            'email' => $this->post('email', ''),
            'password' => $this->post('password', ''),
            'confirm_password' => $this->post('confirm_password', '')
        ]);

        if ($request->fails()) {
            throw new ValidationException($request->errors());
        }

        $this->service->register($request->data());

        //$this->redirect(BASE_URL . '/Public/index.php?page=login');
    }

    public function showLoginForm(): void
    {
        $this->view('auth/login', [
            'pageTitle' => 'Login',
            'errors' => [],
            'old' => []
        ]);
    }

    public function login(): void
    {
        $request = new LoginRequest([
            'email' => $this->post('email', ''),
            'password' => $this->post('password', '')
        ]);

        if ($request->fails()) {
            throw new ValidationException($request->errors());
        }

        $result = $this->service->login($request->data());

        session_regenerate_id(true);

        $_SESSION['user'] = $result->data->toArray();

        $this->redirect(BASE_URL . '/Public/index.php');
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->service->logout();

        $_SESSION = [];

        session_destroy();

        $this->redirect(BASE_URL . '/Public/index.php?page=login');
    }
}