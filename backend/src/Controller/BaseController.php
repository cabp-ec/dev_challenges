<?php

namespace App\Controller;

//use Laminas\Diactoros\Response;

use App\Http\Response;

class BaseController
{
    const DEFAULT_METHOD = 'respondJSON';

    private function respondJSON(array $data, int $status, array $headers = []): Response
    {
        $response = new Response();
        $response->getBody()->write(json_encode($data));

        foreach ($headers as $key => $value) {
            $response = $response->withHeader($key, $value);
        }

        return $response
            ->withHeader(Response::CONTENT_TYPE, Response::CONTENT_TYPE_JSON)
            ->withStatus($status);
    }

    /**
     * Generate a new HTTP response
     *
     * @param array $data
     * @param int $status
     * @param array $headers
     * @param string $format
     * @return Response
     */
    protected function respond(array $data, int $status = 200, array $headers = [], string $format = 'JSON'): Response
    {
        $method = "respond$format";

        if (true !== method_exists($this, $method)) {
            $method = self::DEFAULT_METHOD;
        }

        return $this->$method($data, $status, $headers);
    }
}
