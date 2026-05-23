<?php

/**
 * Base controller shared by all controllers.
 */

class BaseController
{
    /**
     * Render a view
     */
    protected function view(
        string $view,
        array $data = []
    ): void {

        extract($data);

        require BASE_PATH . "/view/{$view}.php";
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
}