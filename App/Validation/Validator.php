<?php

namespace App\Validation;

class Validator
{
    public static function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {

            $value = isset($data[$field])
                ? trim((string)$data[$field])
                : null;

            foreach ($fieldRules as $rule => $ruleValue) {

                /*
                | REQUIRED (FIXED)
                */
                if ($rule === 'required' && $ruleValue === true) {

                    if ($value === null || $value === '') {
                        $errors[$field][] = ucfirst($field) . ' is required.';
                        continue 2; // stop all rules for this field
                    }
                }

                /*
                | EMAIL
                */
                if ($rule === 'email' && $ruleValue === true) {

                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$field][] = 'Invalid email format.';
                    }
                }

                /*
                | MIN
                */
                if ($rule === 'min') {

                    if (strlen($value) < $ruleValue) {
                        $errors[$field][] =
                            ucfirst($field) . " must be at least $ruleValue characters.";
                    }
                }

                /*
                | MAX
                */
                if ($rule === 'max') {

                    if (strlen($value) > $ruleValue) {
                        $errors[$field][] =
                            ucfirst($field) . " must not exceed $ruleValue characters.";
                    }
                }

                /*
                | MATCH
                */
                if ($rule === 'match') {

                    $matchValue = $data[$ruleValue] ?? null;

                    if ($value !== $matchValue) {
                        $errors[$field][] =
                            ucfirst($field) . ' does not match.';
                    }
                }
            }
        }

        return $errors;
    }
}