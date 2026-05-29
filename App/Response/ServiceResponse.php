<?php

namespace App\Response;

class ServiceResponse
{
    public function __construct(
        public bool $success,
        public mixed $data = null,
        public array $errors = []
    ) {}

    /*
    |--------------------------------------------------------------------------
    | SUCCESS RESPONSE
    |--------------------------------------------------------------------------
    */
    public static function success(
        mixed $data = null
    ): self {
        return new self(
            true,
            $data,
            []
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ERROR RESPONSE
    |--------------------------------------------------------------------------
    */
    public static function error(
        array $errors,
        mixed $data = null
    ): self {
        return new self(
            false,
            $data,
            $errors
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ARRAY RESPONSE
    |--------------------------------------------------------------------------
    */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'data' => $this->data,
            'errors' => $this->errors
        ];
    }
}