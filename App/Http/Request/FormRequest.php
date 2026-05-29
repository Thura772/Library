<?php

namespace App\Http\Request;

use App\Validation\Validator;

abstract class FormRequest extends BaseRequest
{
    protected array $errors = [];

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->errors = Validator::validate(
            $this->data,
            static::rules()
        );
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

    // compatibility
    public function validate(): array
    {
        return $this->errors;
    }
}