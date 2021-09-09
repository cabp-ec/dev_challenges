<?php

namespace App\Model\Factory;

use App\Model\BaseModel;
use App\Model\IssueModel;
use App\Model\MemberModel;
use App\Service\StorageService;
use Exception;

class MemberFactory
{
    public function __construct(private StorageService $storage)
    {
    }

    /**
     * @throws Exception
     */
    public function create(string $name, bool $autosave = false): MemberModel
    {
        $model = new MemberModel($name);

        if ($autosave) {
            $model->save();
        }

        return $model;
    }

    /**
     * @throws Exception
     */
    public function make(?string $name): MemberModel
    {
        $key = BaseModel::keyMeUp($name);

        if ($this->storage->exists($key)) {
            // TODO: remove data-parsing, include new data in class constructor
            $member = new MemberModel($key);
            $member->parse($this->storage->get($key));
            return $member;
        }

        return $this->create($name);

//        echo '<pre>';
//        var_dump($issue->toArray());
//        exit;
//        return $issue;
    }
}
