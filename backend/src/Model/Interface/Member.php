<?php

namespace App\Model\Interface;

use App\Model\IssueModel;
use App\Model\MemberModel;

interface Member
{
    /**
     * Check if an issue exits
     *
     * @param string $name
     * @return bool
     */
    static public function exists(string $name): bool;
}
