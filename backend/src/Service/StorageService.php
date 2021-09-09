<?php

namespace App\Service;

use Exception;
use Predis\Client;

//use Redis;
//use RedisException;
use Keygen\Keygen;

class StorageService
{
    const PONG = 'PONG';
    const KEY_LENGTH = 6;
    const REDIS_DEFAULT_HOST = 'localhost';
    const REDIS_DEFAULT_PORT = '6379';

    private array $conn;
    public Client $client;

    public function __construct(
        array        $conn = [],
        private bool $persistent = false,
        private bool $autoconnect = true
    )
    {
        // TODO: get values from .env
        $this->conn = empty($conn) ? [
            'host' => self::REDIS_DEFAULT_HOST,
            'port' => self::REDIS_DEFAULT_PORT,
//            'timeout' => 0.0,
//            'reserved' => null,
//            'retryInterval' => 0,
//            'readTimeout' => 0.0,
        ] : $conn;

        $this->connect();
    }

    private function connect()
    {
        unset($this->client);

        try {
            $this->client = new Client($this->conn);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

//        echo '<pre>';
//        var_dump($this->conn);
//        exit;
    }

    /**
     * Close the current connection
     */
    public function close()
    {
        $this->client->disconnect();
    }

    /**
     * @param string $key
     * @param string|array $value
     */
    public function set(string $key, string|array $value)
    {
        if (!$this->persistent) {
            $this->connect();
        }

        $r = $this->client->set($key, json_encode($value));

        if (!$this->persistent) {
            $this->client->disconnect();
        }

//        echo '<pre>';
//        var_dump((bool)$r);
//        exit;

        return (bool)$r;
    }

    public function get(string $key)
    {
        if (!$this->persistent) {
            $this->connect();
        }

        if ($this->client->exists($key)) {
            return json_decode($this->client->get($key), true);
        }

        if (!$this->persistent) {
            $this->client->disconnect();
        }

        return false;
    }

    public function exists(string $key)
    {
        try {
            if (!$this->persistent) {
                $this->connect();
            }

            $output = (bool)$this->client->exists($key);

            if (!$this->persistent) {
                $this->client->disconnect();
            }

            return $output;
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

        return false;
//        return (bool)$this->client->exists($key);
    }

    /**
     * Run a health-check for redis features
     *
     * @return array
     */
    public function health(): array
    {
        $output = [];

        try {
            $pong = $this->client->ping();
            $output['ping'] = $pong === self::PONG;

            // TODO: other health checks and failure interpretation/messaging
        } catch (RedisException $e) {
            echo $e->getMessage();
        }

        return $output;
    }
}
