<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects;

use Ingenerator\StubObjects\Configurator\AttributeOrDefaultStubFactoryConfigurator;
use Ingenerator\StubObjects\Configurator\AttributeOrGuessStubAsConfigurator;
use Ingenerator\StubObjects\Configurator\AttributeOrGuessStubDefaultConfigurator;
use Ingenerator\StubObjects\Configurator\StubFactoryConfigurator;
use ReflectionClass;
use Throwable;

class StubObjects
{
    private readonly StubbingContext $context;
    private readonly StubFactoryConfigurator $factory_config;

    public function __construct(
        private readonly array $stubbable_class_patterns,
        ?StubFactoryConfigurator $factory_config = NULL,
    ) {
        $this->context = new StubbingContext($this, $this->stubbable_class_patterns);
        $this->factory_config = $factory_config ?? new AttributeOrDefaultStubFactoryConfigurator(
            new AttributeOrGuessStubDefaultConfigurator(StandardConfig::loadDefaultValueGuessers()),
            new AttributeOrGuessStubAsConfigurator(StandardConfig::loadCasterGuessers()),
        );
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     *
     * @return T
     */
    public function stub(string $class, array $values = []): object
    {
        try {
            $reflection = new ReflectionClass($class);
            $factory = $this->factory_config->getStubFactory($reflection);

            return $factory->make($values, $this->context);
        } catch (Throwable $e) {
            throw new FailedToStubObjectException(
                sprintf('Could not stub a %s: [%s] %s', $class, $e::class, $e->getMessage()),
                previous: $e,
            );
        }
    }
}
