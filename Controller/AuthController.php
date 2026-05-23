<?php
// File: Controller/AuthController.php

require_once BASE_PATH . '/Controller/BaseController.php';

class AuthController extends BaseController 
{
    private AuthService $authService;

    public function __construct(AuthService $authService) 
    {
        $this->authService = $authService;
    }

    /**
     * GET /register
     * Displays the registration form
     */
    public function showRegisterForm(): void 
    {
        $this->view('auth/register', [
            'pageTitle' => 'Create an Account',
            'errors'    => [],
            'old'       => []
        ]);
    }

    /**
     * POST /register
     * Handles the form submission
     */
    public function register(): void 
{
    $name             = $this->post('name', '');
    $email            = $this->post('email', '');
    $password         = $this->post('password', '');
    $confirmPassword  = $this->post('confirm_password', '');

    $errors = $this->authService->register([
        'name'              => $name,
        'email'             => $email,
        'password'          => $password,
        'confirm_password'  => $confirmPassword
    ]);

    if (!empty($errors)) {
        $this->view('auth/register', [
            'pageTitle' => 'Create an Account',
            'errors'    => $errors,
            'old'       => [
                'name'  => $name,
                'email' => $email
            ]
        ]);
        return;
    }

    $this->redirect('index.php?page=login');
}
}