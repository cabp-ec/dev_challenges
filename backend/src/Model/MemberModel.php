<?php

namespace App\Model;

use App\Model\Interface\Member;
use Exception;

class MemberModel extends BaseModel
{
    private ?IssueModel $issue = null;
    protected string $status = 'waiting';
    protected float $value = 0.0;

    public function __construct(protected string $name)
    {
        $this->key = BaseModel::keyMeUp($this->name);
        $this->keys = ['name', 'status', 'value'];

        parent::__construct();
    }

    public function getIssue(): IssueModel|null
    {
        return $this->issue;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws Exception
     */
    public function setName(string $value)
    {
        $this->name = $value;
        $this->save();
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    public function setIssue(IssueModel &$value): void
    {
        $this->issue = $value;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
