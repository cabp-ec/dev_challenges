<?php

namespace App\Model\Interface;

use App\Model\IssueModel;
use App\Model\MemberModel;

interface Issue
{
    /**
     * Get the issue code
     *
     * @return string
     */
    public function getCode(): string;

    /**
     * Set description
     *
     * @param string $value
     */
    public function setOverview(string $value): void;

    /**
     * Set members
     *
     * @param array $value
     */
    public function setMembers(array $value);

    /**
     * Add a new member
     *
     * @param MemberModel $value
     */
    public function addMember(MemberModel $value): void;

    /**
     * Set status
     *
     * @param string $value
     */
    public function setStatus(string $value);
}
