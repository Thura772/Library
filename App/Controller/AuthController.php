<?php

namespace App\Controller;

use App\Service\AuthService;
use App\Http\Request\RegisterRequest;
use App\Http\Request\LoginRequest;

class AuthController extends BaseController
{
    public function __construct(
        private AuthService $service
    ) {}

    /*
    |--------------------------------------------------------------------------
    | REGISTER FORM
    |--------------------------------------------------------------------------
    */
    public function showRegisterForm(): void
    {
        $this->view('auth/register', [
            'pageTitle' => 'Create Account',
            'errors' => [],
            'old' => [],
            'section' => null,
            'page' => 'register',
            'hideSearch' => true
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER ACTION
    |--------------------------------------------------------------------------
    */
    public function register(): void
    {
        $request = new RegisterRequest([
            'name' => $this->post('name', ''),
            'email' => $this->post('email', ''),
            'password' => $this->post('password', ''),
            'confirm_password' => $this->post('confirm_password', '')
        ]);

        $this->service->register($request->toArray());

        $this->redirect(BASE_URL . '/Public/index.php?page=login');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN FORM
    |--------------------------------------------------------------------------
    */
    public function showLoginForm(): void
    {
        $this->view('auth/login', [
            'pageTitle' => 'Login',
            'errors' => [],
            'old' => [],
            'section' => null,
            'page' => 'login',
            'hideSearch' => true
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN ACTION
    |--------------------------------------------------------------------------
    */
    public function login(): void
    {
        $request = new LoginRequest([
            'email' => $this->post('email', ''),
            'password' => $this->post('password', '')
        ]);

        $result = $this->service->login($request->credentials());

        session_regenerate_id(true);
        $_SESSION['user'] = $result->data->toArray();

        $this->redirect(BASE_URL . '/Public/index.php');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(): void
    {
        // optional service call (no session logic inside service)
        $this->service->logout();

        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            session_destroy();
        }
        $this->redirect(BASE_URL . '/Public/index.php?page=login');
    }
}
