<?php

namespace App\Model;

use Keygen\Keygen;
use App\Model\Interface\Issue;

class IssueModel extends BaseModel implements Issue
{
    protected string $status = 'voting';
    protected string $overview = '';
    protected float $avg = 0.0;
    protected array $members = [];

    /**
     * @param string $code
     */
    public function __construct(protected string $code)
    {
        $this->key = $this->code;
        $this->keys = ['code', 'status', 'overview', 'avg', 'members'];

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @inheritDoc
     */
    public function setOverview(string $value): void
    {
        $this->overview = $value;
    }

    /**
     * @inheritDoc
     */
    public function setMembers(array $value)
    {
        $this->members = $value;
    }

    /**
     * @inheritDoc
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @inheritDoc
     */
    public function addMember(MemberModel $value, bool $moderator = false): void
    {
        if ($value->getIssue() === $this) {
            return;
        }

        $value->setIssue($this);
        $this->members[] = $value;
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function setStatus(string $value)
    {
        $this->status = $value;
    }

    public function vote(MemberModel $member)
    {
        $index = $this->memberExists($member);
        $this->members[$index] = $member;
    }

    public function voted(MemberModel $member)
    {
        if ('voted' !== $member->getStatus()) {
            return false;
        }

        return true;
    }

    public function memberExists(MemberModel $value)
    {
        // TODO: transform this into a binary search
        /** @var MemberModel $member */
        foreach ($this->members as $index => $member) {
            if ($value->getName() === $member['name']) {
                return $index;
            }
        }

        return false;
    }
}
