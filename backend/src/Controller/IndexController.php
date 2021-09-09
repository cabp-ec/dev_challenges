<?php

namespace App\Controller;

use Base;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends BaseController
{
    public function index(Base $f3): \App\Http\Response
    {
        $output = [
            'name' => 'Poker API',
            'type' => 'RPC',
            'endpoints' => [
                '/issue/@issue/join' => [
                    'description' => 'Create a new issue or join an existent one.',
                    'payload' => [
                        'name' => [
                            'type' => 'string',
                            'mandatory' => true,
                        ],
                    ],
                ],
            ],
        ];

        return $this->respond($output, Response::HTTP_OK);
    }
}
