<?php

declare(strict_types=1);

namespace App\Core;

use Throwable;
use App\Exceptions\ValidationException;
use App\Exceptions\NotFoundException;

class ErrorHandler
{
 public static function register(): void
{
    set_exception_handler([self::class, 'handleException']);

    // DO NOT convert ALL warnings into exceptions
    // This is what is breaking your login
}

   

    /*
    |--------------------------------------------------------------------------
    | HANDLE PHP ERRORS
    |--------------------------------------------------------------------------
    */
    public static function handleError(
        int $severity,
        string $message,
        string $file,
        int $line
    ): bool {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

    /*
    |--------------------------------------------------------------------------
    | HANDLE EXCEPTIONS
    |--------------------------------------------------------------------------
    */
    public static function handleException(Throwable $e): void
    {
        http_response_code(500);

        // ensure session exists
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        /*
        |--------------------------------------------------------------------------
        | LOG ERROR
        |--------------------------------------------------------------------------
        */
        error_log(
            sprintf(
                "[%s] %s in %s:%d",
                get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            )
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDATION ERROR
        |--------------------------------------------------------------------------
        */
        if ($e instanceof ValidationException) {

            $_SESSION['errors'] = $e->getErrors();

            $redirect = $_SERVER['HTTP_REFERER']
                ?? BASE_URL . '/Public/index.php?page=login';

            header('Location: ' . $redirect);
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | 404 ERROR
        |--------------------------------------------------------------------------
        */
        if ($e instanceof NotFoundException) {

            http_response_code(404);

            require BASE_PATH . '/view/errors/404.php';
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | GENERIC ERROR
        |--------------------------------------------------------------------------
        */
        require BASE_PATH . '/view/errors/500.php';
        exit;
    }
}