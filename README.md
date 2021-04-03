# Codeception Extension Dependency Injection Yii3

By default, Codeception has a simple DI.
It knows how to find dependencies only by namespace paths.

But sometimes it is necessary to test the application taking into account the specifics of the configuration. 
For example, when you need to resolve dependencies through interfaces.
This extension is intended for this case.

To use it, include it in the required configuration file, it can be codeception.yml or one of suite unit.yml.
And provide the paths to your configurations.
```yml
extensions:
  enabled:
      - tests\extensions\CodeceptionExtDI
  config:
      tests\extensions\CodeceptionExtDI:
          - ./config/main-definitions.php
          - ./config/test-definitions.php
```
Then you can inject dependencies on your project, for example, in this way:
```php
    public function _dependencies(CalculatorInterface $calculator)
    {
        $this->calculator = $calculator;
    }
```

And also you can adapt it for your project by replacing the Container and Injector with your own.
