<?php

namespace App\Model;

use App\Kernel;
use App\Model\Interface\Model;
use App\Service\StorageService;
use Exception;

class BaseModel implements Model
{
    const STORAGE_CLASS = 'App\Service\StorageService';

    /** @var StorageService $storage */
    protected $storage;
    protected string $key;
    protected array $keys = [];

    public function __construct()
    {
        // TODO: find a better way to get the redis service from the DI container
        $this->storage = Kernel::di(self::STORAGE_CLASS);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $output = [];

        foreach ($this->keys as $key) {
            $output[$key] = is_array($this->{$key}) ? $this->checkArray($this->{$key}) : $this->{$key};
        }

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function toJSON(): string
    {
        return json_encode($this->toArray());
    }

    private function checkArray(array $values)
    {
        $output = [];

        foreach ($values as $value) {
            if ($value instanceof BaseModel) {
                $output[] = $value->toArray();
            }
        }

        return empty($output) ? $values : $output;
    }

    /**
     * @throws Exception
     */
    public function save(): bool
    {
        $value = [];

        foreach ($this->keys as $key) {
            if (property_exists($this, $key) && !is_null($this->{$key})) {
                $value[$key] = is_array($this->{$key}) ? $this->checkArray($this->{$key}) : $this->{$key};
            }
        }

        if (!empty($value)) {
            return $this->storage->set($this->key, $value);
        }

        throw new Exception('Can\'t save an empty model');
    }

    /**
     * @inheritDoc
     */
    public function parse(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    static public function keyMeUp(string $value): string
    {
        // TODO: replace invalid characters
        return str_replace(' ', '_', strtolower($value));
    }
}
