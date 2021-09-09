<?php

namespace App\Service;

final class DataValidationService
{
    /**
     * Validate a simple string
     *
     * @param string $value
     * @param array $rules
     * @return bool
     */
    public function string(string $value, array $rules = []): bool
    {
        // TODO: put your magic here
        return true;
    }

    /**
     * Validate a group of input data (e.g. from forms, API)
     *
     * @param array $values
     * @param array $rules
     * @return bool
     */
    public function input(array $values, array $rules = []): bool
    {
        // TODO: put your magic here
        return true;
    }
}
