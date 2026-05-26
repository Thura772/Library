<?php

namespace App\Validation;

class Validator
{
    public static function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {

            $value = $data[$field] ?? null;

            foreach ($fieldRules as $rule) {

                // REQUIRED
                if ($rule === 'required') {
                    if (empty($value) && $value !== '0') {
                        $errors[$field] = ucfirst($field) . ' is required.';
                        break;
                    }
                }

                // EMAIL
                if ($rule === 'email') {
                    if (!empty($value) &&
                        !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$field] = 'Invalid email format.';
                        break;
                    }
                }

                // MIN
                if (str_starts_with($rule, 'min:')) {
                    $min = (int) explode(':', $rule)[1];

                    if (!empty($value) && strlen($value) < $min) {
                        $errors[$field] =
                            ucfirst($field) .
                            " must be at least $min characters.";
                        break;
                    }
                }

                // CONFIRMED
                if ($rule === 'confirmed') {

    $confirmField = 'confirm_' . $field;

    if (($data[$field] ?? null) !== ($data[$confirmField] ?? null)) {
        $errors[$field] = 'Passwords do not match.';
        break;
    }
}
            }
        }

        return $errors;
    }
}