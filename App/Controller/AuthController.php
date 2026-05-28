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

        if ($request->fails()) {
            $this->view('auth/register', [
                'pageTitle' => 'Create Account',
                'errors' => $request->errors(),
                'old' => $request->data()
            ]);
            return;
        }

        $result = $this->service->register($request);

        if (!$result['success']) {
            $this->view('auth/register', [
                'pageTitle' => 'Create Account',
                'errors' => $result['errors'],
                'old' => $request->data()
            ]);
            return;
        }

        $this->redirect(BASE_URL . '/Public/index.php?page=login');
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

        if ($request->fails()) {
            $this->view('auth/login', [
                'pageTitle' => 'Login',
                'errors' => $request->errors(),
                'old' => $request->data()
            ]);
            return;
        }

        $result = $this->service->login($request);

        if (!empty($result['errors'])) {
            $this->view('auth/login', [
                'pageTitle' => 'Login',
                'errors' => $result['errors'],
                'old' => $request->data()
            ]);
            return;
        }

        // SECURITY FIX
        session_regenerate_id(true);

        // STORE USER SESSION (DTO)
        $_SESSION['user'] = $result['user']->toArray();

        $this->redirect(BASE_URL . '/Public/index.php');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->service->logout();

        // extra safety cleanup
        $_SESSION = [];

        session_destroy();

        $this->redirect(BASE_URL . '/Public/index.php?page=login');
    }
}