<?php

namespace App\Http\Request;

abstract class BaseRequest
{
    protected array $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function all(): array
    {
        return $this->data;
    }

    abstract public function validate(): array;
}