<?php

namespace tests\_support\extensions;

use Codeception\Event\TestEvent;
use Codeception\Events;
use Codeception\Extension;
use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Di\Container;
use Yiisoft\Injector\Injector;

/**
 * Class CodeceptionExtDI
 * Extension Codeception
 * Allows injecting dependencies using project configurations
 * Configuration paths are written in the place where the extension is declared.
 * extensions:
 *   enabled:
 *       - tests\_support\extensions\CodeceptionExtDI
 *   config:
 *       tests\_support\extensions\CodeceptionExtDI:
 *           - ./config/di/definitions.php
 *           - definitions.php
 * @package tests\_support\extensions
 */
class CodeceptionExtDI extends Extension
{
    /**
     * Method through which it is possible to inject dependencies
     * @var string
     */
    protected $injectMethodName = '_dependencies';

    protected static array $events = [
        Events::TEST_BEFORE => 'injectDependencies',
    ];

    private $container;

    /**
     * @param TestEvent $event
     * @throws \ReflectionException
     * @throws \Yiisoft\Factory\Exceptions\InvalidConfigException
     */
    public function injectDependencies(TestEvent $event): void
    {
        $test = $event->getTest();

        if (!method_exists($test, $this->injectMethodName)) {
            return;
        }

        $injector = new Injector($this->getContainer());
        $injector->invoke([$test, $this->injectMethodName]);
    }


    /**
     * @return Container
     * @throws \Yiisoft\Factory\Exceptions\InvalidConfigException
     */
    private function getContainer(): Container
    {
        if (is_object($this->container)) {
            return $this->container;
        }

        $configs = array_map(fn($path) => require_once $path, $this->config);
        $definitions = ArrayHelper::merge(...$configs);

        return $this->container = new Container($definitions);
    }

}
