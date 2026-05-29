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
    | SHOW REGISTER FORM
    |--------------------------------------------------------------------------
    */
    public function showRegisterForm(): void
    {
        $this->view('auth/register', [
            'pageTitle' => 'Create Account',
            'errors' => [],
            'old' => []
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER
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

        $result = $this->handleRequest(
            $request,
            'auth/register',
            'Create Account',
            fn ($data) => $this->service->register($data)
        );

        if (!$result) {
            return;
        }

        $this->redirect(
            BASE_URL .
            '/Public/index.php?page=login'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW LOGIN FORM
    |--------------------------------------------------------------------------
    */
    public function showLoginForm(): void
    {
        $this->view('auth/login', [
            'pageTitle' => 'Login',
            'errors' => [],
            'old' => []
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    public function login(): void
    {
        $request = new LoginRequest([
            'email' => $this->post('email', ''),
            'password' => $this->post('password', '')
        ]);

        $result = $this->handleRequest(
            $request,
            'auth/login',
            'Login',
            fn ($data) => $this->service->login($data)
        );

        if (!$result) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */
        session_regenerate_id(true);

        /*
        |--------------------------------------------------------------------------
        | STORE USER
        |--------------------------------------------------------------------------
        */
        $_SESSION['user'] =
            $result->data->toArray();

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */
        $this->redirect(
            BASE_URL .
            '/Public/index.php'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(): void
    {
        if (
            session_status() ===
            PHP_SESSION_NONE
        ) {
            session_start();
        }

        $this->service->logout();

        $_SESSION = [];

        session_destroy();

        $this->redirect(
            BASE_URL .
            '/Public/index.php?page=login'
        );
    }
}