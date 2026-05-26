<?php
// File: Controller/AuthController.php
namespace App\Controller;

use App\Service\AuthService;
use Exception;
use App\Controller\BaseController;

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
    $name            = $this->post('name', '');
    $email           = $this->post('email', '');
    $password        = $this->post('password', '');
    $confirmPassword = $this->post('confirm_password', '');

    $result = $this->authService->register([
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'confirm_password' => $confirmPassword
    ]);

    // ❌ IF ERROR → BACK TO FORM
    if (!$result['success']) {

        $this->view('auth/register', [
            'pageTitle' => 'Create an Account',
            'errors' => $result['errors'],
            'old' => [
                'name' => $name,
                'email' => $email
            ]
        ]);

        return;
    }

    // ✅ SUCCESS → REDIRECT
    $this->redirect(BASE_URL . '/Public/index.php?page=login');
    exit;
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
    $email    = $this->post('email', '');
    $password = $this->post('password', '');

    $result = $this->authService->login([
        'email' => $email,
        'password' => $password
    ]);

    if (!empty($result['errors'])) {

        $this->view('auth/login', [
            'pageTitle' => 'Login',
            'errors' => $result['errors'],
            'old' => [
                'email' => $email
            ]
        ]);

        return;
    }

    // SECURITY
    session_regenerate_id(true);

    $_SESSION['user'] = [
    'id' => $result['user']->getId(),
    'name' => $result['user']->getName(),
    'email' => $result['user']->getEmail(),
    'role' => $result['user']->getRole()
];

    $this->redirect(BASE_URL . '/Public/index.php');
}

public function logout()
{
    session_start();

    $_SESSION = [];

    session_destroy();

   $this->redirect(BASE_URL . './Public/index.php');
    exit;
}

}