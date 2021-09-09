<?php

namespace App\Http\Middleware;

use Base;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ControllerMiddleware implements MiddlewareInterface
{
    private Base $f3;
    private $controller;
    private array $params;
    private $alias;
    private string $hooks;

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->f3->call(
            $this->controller,
            [$this->f3, $this->params, $this->alias],
            $this->hooks
        );
    }

    /**
     * @param Base $f3
     */
    public function setF3(Base &$f3): void
    {
        $this->f3 = $f3;
    }

    /**
     * @param mixed $controller
     */
    public function setController($controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @param string $hooks
     */
    public function setHooks(string $hooks = 'beforeroute,afterroute'): void
    {
        $this->hooks = $hooks;
    }
}
