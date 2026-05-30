<?php

namespace App\Validation;

class Validator
{
    public static function validate(array $data, array $rules): array
    {
        $errors = [];

        $labels = [
            'name' => 'Full Name',
            'email' => 'Email',
            'password' => 'Password',
            'confirm_password' => 'Confirm Password',
        ];

        foreach ($rules as $field => $fieldRules) {

            $value = trim((string)($data[$field] ?? ''));

            foreach ($fieldRules as $rule => $ruleValue) {

                if ($rule === 'required' && $ruleValue === true) {
                    if ($value === '') {
                        $errors[$field] = ($labels[$field] ?? $field) . ' is required';
                        break;
                    }
                }

                if ($rule === 'email' && $ruleValue === true) {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$field] = 'Invalid email format';
                        break;
                    }
                }

                if ($rule === 'min') {
                    if (strlen($value) < $ruleValue) {
                        $errors[$field] = "Must be at least $ruleValue characters";
                        break;
                    }
                }

                if ($rule === 'max') {
                    if (strlen($value) > $ruleValue) {
                        $errors[$field] = ($labels[$field] ?? $field) . " must be max $ruleValue characters";
                        break;
                    }
                }

                if ($rule === 'match') {
                    if (($data[$ruleValue] ?? null) !== $value) {
                        $errors[$field] = 'Passwords do not match';
                        break;
                    }
                }

                if ($rule === 'strong_password' && $ruleValue === true) {
                    if (
                        !preg_match('/[A-Z]/', $value) ||
                        !preg_match('/[a-z]/', $value) ||
                        !preg_match('/[0-9]/', $value)
                    ) {
                        $errors[$field] = 'Password must contain uppercase, lowercase, and number';
                        break;
                    }
                }
            }
        }

        return $errors;
    }
}
