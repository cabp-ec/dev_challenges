<?php

namespace App\Http\Middleware;

use App\Http\Response;
use Base;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // TODO: do your magic here...
        $results = false;

        if (!$results) {
            $response = new Response();
            $response->getBody()->write(json_encode(['auth' => 'you shall not pass!']));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(Response::HTTP_UNAUTHORIZED);

//            throw new \Exception('Auth: you shall not pass!');
        }

        return $handler->handle($request);
    }
}
