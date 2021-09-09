<?php

namespace App\Http;

use App\Container\Dice;
use App\Http\Middleware\ControllerMiddleware;
use Laminas\Diactoros\ServerRequestFactory;
use Relay\Relay;

class Router
{
    const PIPELINE_DEFAULT = 'DEFAULT';
    const CONTROLLER_SIGNATURE = 'App\Controller\%sController->%s';
    const MIDDLEWARE_ERROR_HANDLER = 'Franzl\Middleware\Whoops\WhoopsMiddleware';
    const MIDDLEWARE_CONTROLLER = 'App\Http\Middleware\ControllerMiddleware';

    public function __construct(
        private \Base &$f3,
        private Dice  $container,
        private array $routes,
        private array $pipelines,
    )
    {
        // Setup Routes
        foreach ($this->routes as $route => $params) {
            $modifier = $params['modifier'] ?? '';
            $pattern = strtoupper($params['httpMethod']) . " $route $modifier";
            $handler = sprintf(self::CONTROLLER_SIGNATURE, $params['controller'], $params['method']);
            $pipeline = $params['pipeline'] ?? self::PIPELINE_DEFAULT;

            $this->f3->route(
                $pattern,
                fn(\Base $f3, $params, $alias) => $this->handleRoute($handler, $params, $alias, $pipeline)
            );
        }
    }

    /**
     * Handle a F3 route
     *
     * @param $handler
     * @param $params
     * @param $alias
     * @param $pipe
     * @return bool
     */
    private function handleRoute($handler, $params, $alias, $pipe): bool
    {
        // Error Handler
        $pipeline = [$this->container->create(self::MIDDLEWARE_ERROR_HANDLER)];

        // Other Middlewares
        if ($pipe !== self::PIPELINE_DEFAULT) {
            foreach ($this->pipelines[$pipe] as $class => $rule) {
                $pipeline[] = $this->container->create($class);
            }
        }

        // Controller Middleware
        /** @var ControllerMiddleware $controllerMiddleware */
        $controllerMiddleware = $this->container->create(self::MIDDLEWARE_CONTROLLER, []);
        $controllerMiddleware->setF3($this->f3);
        $controllerMiddleware->setController($handler);
        $controllerMiddleware->setParams($params);
        $controllerMiddleware->setAlias($alias);
        $controllerMiddleware->setHooks();
        $pipeline[] = $controllerMiddleware;

        // Run Pipeline
        $relay = new Relay($pipeline);

        /** @var Response $response */
        $response = $relay->handle(ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        ));
        $response->send();

        return false != $response;
    }
}
