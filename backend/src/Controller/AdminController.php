<?php

namespace App\Controller;

use Base;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends BaseController
{
    public function index(Base $f3): \App\Http\Response
    {
        $output = [
            'name' => 'Admin Endpoint',
            'type' => 'RPC',
            'endpoints' => [
                '/admin' => [
                    'description' => 'Administrator options...',
                ],
            ],
        ];

        $r = $this->respond($output, Response::HTTP_OK);
//        var_dump($r);;

        return $r;
    }
}
