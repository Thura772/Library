<?php

namespace App\Http\Request;

use App\Validation\Validator;

abstract class FormRequest
{
    protected array $data;
    protected array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;

        // ✅ AUTO VALIDATION
        $this->errors = Validator::validate($this->data, static::rules());
    }

    abstract public static function rules(): array;

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function data(): array
    {
        return $this->data;
    }
}