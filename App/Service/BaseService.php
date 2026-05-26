<?php
namespace App\Service;

abstract class BaseService
{
    protected function success(array $data = []): array
    {
        return array_merge([
            'success' => true,
            'errors' => []
        ], $data);
    }

    protected function fail(array $errors, array $data = []): array
    {
        return array_merge([
            'success' => false,
            'errors' => $errors
        ], $data);
    }
}

?>