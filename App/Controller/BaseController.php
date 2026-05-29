<?php

namespace App\Controller;

class BaseController
{
    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */
    protected function view(
    string $view,
    array $data = []
): void {

    $data['errors'] = $data['errors'] ?? [];
    $data['old'] = $data['old'] ?? [];

    extract($data);

    $file = BASE_PATH . '/view/' . $view . '.php';

    if (!file_exists($file)) {
        die("View not found: " . $file);
    }

    require $file;
}

    /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */
    protected function redirect(
        string $url
    ): void {

        header("Location: {$url}");
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | GET INPUT
    |--------------------------------------------------------------------------
    */
    protected function get(
        string $key,
        $default = null
    ) {
        return $_GET[$key] ?? $default;
    }

    /*
    |--------------------------------------------------------------------------
    | POST INPUT
    |--------------------------------------------------------------------------
    */
    protected function post(
        string $key,
        $default = null
    ) {
        return $_POST[$key] ?? $default;
    }

    /*
    |--------------------------------------------------------------------------
    | JSON RESPONSE
    |--------------------------------------------------------------------------
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

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */
    protected function requireAuth(): void
    {
        if (empty($_SESSION['user'])) {

            $this->redirect(
                BASE_URL .
                '/Public/index.php?page=login'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CURRENT USER
    |--------------------------------------------------------------------------
    */
    protected function currentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK AUTH
    |--------------------------------------------------------------------------
    */
    protected function isAuthenticated(): bool
    {
        return !empty($_SESSION['user']);
    }

    /*
    |--------------------------------------------------------------------------
    | HANDLE FORM REQUEST
    |--------------------------------------------------------------------------
    */
   protected function handleRequest(
    $request,
    string $view,
    string $title,
    callable $callback
): mixed {

    $errors = $request->errors();

    if (!empty($errors)) {
        $this->view($view, [
            'pageTitle' => $title,
            'errors' => $errors,
            'old' => $request->all()
        ]);

        return false;
    }

    try {

        return $callback($request->all());

    } catch (\App\Exceptions\ValidationException $e) {

        $this->view($view, [
            'pageTitle' => $title,
            'errors' => $e->getErrors(),
            'old' => $request->all()
        ]);

        return false;

    } catch (\Throwable $e) {

        //  THIS IS WHAT PREVENTS WHITE PAGE
     error_log($e->getMessage());

        $this->view('errors/500', [
            'pageTitle' => 'Error',
            'errors' => ['general' => 'Something went wrong'],
            'old' => []
        ]);

        return false;
    }
}
}