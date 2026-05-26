<?php

namespace App\Controller;

/**
 * Base controller shared by all controllers.
 */

class BaseController
{
    /**
     * Render a view
     */
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        $file = BASE_PATH . '/view/' . $view . '.php';


        if (!file_exists($file)) {
            die("View not found: " . $file);
        }

        require $file;
    }

    /**
     * Redirect to another page
     */
    protected function redirect(
        string $url
    ): void {

        header("Location: {$url}");
        exit;
    }

    /**
     * Get GET request input
     */
    protected function get(
        string $key,
        $default = null
    ) {
        return $_GET[$key] ?? $default;
    }

    /**
     * Get POST request input
     */
    protected function post(
        string $key,
        $default = null
    ) {
        return $_POST[$key] ?? $default;
    }

    /**
     * Return JSON response
     */
    protected function json(
        $data,
        int $status = 200
    ): void {

        http_response_code($status);

        header('Content-Type: application/json');

        echo json_encode($data);

        exit;
    }
    protected function requireAuth(): void
{
    if (empty($_SESSION['user'])) {

        $this->redirect(
            BASE_URL . '/Public/index.php?page=login'
        );
    }
}
protected function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}
protected function isAuthenticated(): bool
{
    return !empty($_SESSION['user']);
}
}