<?php

namespace App\Validation;

class Validator
{
    public static function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {

            $value = $data[$field] ?? null;

            foreach ($fieldRules as $ruleKey => $ruleValue) {

                // REQUIRED
                if ($ruleKey === 'required' && $ruleValue === true) {
                    if (empty($value) && $value !== '0') {
                        $errors[$field] = ucfirst($field) . ' is required.';
                        break;
                    }
                }

                // EMAIL
                if ($ruleKey === 'email' && $ruleValue === true) {
                    if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$field] = 'Invalid email format.';
                        break;
                    }
                }

                // MIN
                if ($ruleKey === 'min') {
                    if (!empty($value) && strlen($value) < $ruleValue) {
                        $errors[$field] = ucfirst($field) . " must be at least $ruleValue characters.";
                        break;
                    }
                }

                // MAX
                if ($ruleKey === 'max') {
                    if (!empty($value) && strlen($value) > $ruleValue) {
                        $errors[$field] = ucfirst($field) . " must not exceed $ruleValue characters.";
                        break;
                    }
                }

                // MATCH (IMPORTANT FIX)
                if ($ruleKey === 'match') {
                    $matchValue = $data[$ruleValue] ?? null;

                    if ($value !== $matchValue) {
                        $errors[$field] = ucfirst($field) . ' does not match.';
                        break;
                    }
                }
            }
        }

        return $errors;
    }
}