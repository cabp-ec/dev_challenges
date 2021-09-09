<?php

namespace App\Model\Interface;

use App\Model\IssueModel;
use App\Model\MemberModel;

interface Model
{
    /**
     * Return fields as array
     *
     * @return array
     */
    function toArray(): array;

    /**
     * Return fields as JSON string
     *
     * @return string
     */
    function toJSON(): string;

    /**
     * Save an instance of the model
     *
     * @return bool
     */
    function save(): bool;

    /**
     * Parse data array into a model
     *
     * @param array $data
     */
    function parse(array $data): void;
}
