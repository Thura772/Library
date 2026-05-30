<?php

namespace App\Http\Request;

use App\Validation\Validator;
use App\Exceptions\ValidationException;

abstract class FormRequest
{
    protected array $data;
    protected array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;

        $this->errors = Validator::validate(
            $this->data,
            static::rules()
        );

        // ✅ THIS IS THE KEY FIX
        if (!empty($this->errors)) {
            throw new ValidationException($this->errors);
        }
    }

    abstract public static function rules(): array;

    public function all(): array
    {
        return $this->data;
    }
}
