<?php

namespace App\Model\Factory;

use App\Model\IssueModel;
use App\Service\StorageService;
use Exception;

class IssueFactory
{
    public function __construct(private StorageService $storage)
    {
    }

    /**
     * @throws Exception
     */
    public function create(string $code, bool $autosave = false): IssueModel
    {
        // TODO: validate $fields here
        $model = new IssueModel($code);

        if ($autosave) {
            $model->save();
        }

        return $model;
    }

    /**
     * @throws Exception
     */
    public function make(?string $key): IssueModel
    {
        if ($this->storage->exists($key)) {
            // TODO: remove data-parsing, include new data in class constructor
            $issue = new IssueModel($key);
            $issue->parse($this->storage->get($key));
            return $issue;
        }

        return $this->create($key, true);
    }
}
