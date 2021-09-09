<?php

namespace App;

use App\Container\Dice;
use App\Http\Router;
use Base;

final class Kernel extends \Prefab
{
    private static $instance = null;

    private Base $f3;
    private Dice $container;
    private Router $router;
    private array $settings;

    private function __construct()
    {
    }

    protected function __clone()
    {
    }

    /**
     * @throws \Exception
     */
    public function __wakeup()
    {
        throw new \Exception('Can\'t wake up');
    }

    /**
     * Get the only instance of this class
     *
     * @return Kernel
     */
    static public function getInstance(): Kernel
    {
        if (null !== self::$instance) {
            return self::$instance;
        }

        self::$instance = new Kernel();
        return self::$instance;
    }

    /**
     * Get an object from the DI container
     *
     * @param string $class
     * @return object
     */
    public function create(string $class)
    {
        return $this->container->create($class);
    }

    static public function di(string $class)
    {
        $i = self::getInstance();
        return $i->create($class);
    }

    /**
     * @param array $settings
     */
    public function setup(array $settings = [])
    {
        $this->f3 = Base::instance();
        // F3 autoloader for application business code
        $this->f3->set('AUTOLOAD', $this->settings['autoload'] ?? (__DIR__ . DIRECTORY_SEPARATOR));

        $this->settings = $settings;
        $this->setContainer();
        $this->setRouter();
    }

    /**
     * Set up the DI container
     */
    private function setContainer()
    {
        $this->container = new Dice();

        foreach ($this->settings['di'] as $class => $rule) {
            $this->container->addRule($class, $rule);
        }

        $this->f3->set('CONTAINER', fn($class) => $this->container->create($class));
    }

    /**
     * Set up the Router and its middlewares
     */
    private function setRouter()
    {
        $this->router = new Router(
            $this->f3,
            $this->container,
            $this->settings['routes'],
            $this->settings['pipelines']
        );
    }

    /**
     * Run the F3 app
     */
    public function run()
    {
        $this->f3->run();
    }
}
