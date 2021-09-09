<?php

namespace App\Service;

final class HttpService
{
    private array $headers = [];

    /**
     * @param array $settings
     */
    public function __construct(private array $settings)
    {
    }

    /**
     * Perform a GET request
     *
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return array
     */
    public function get(string $url, array $data = [], array $headers = []): array
    {
        // TODO: put your code here
        return ['to be implemented'];
    }

    /**
     * Perform a POST request
     *
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return array
     */
    public function post(string $url, array $data = [], array $headers = []): array
    {
        // TODO: put your code here
        return ['to be implemented'];
    }
}
