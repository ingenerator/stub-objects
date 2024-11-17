<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects;

use Ingenerator\StubObjects\Configurator\AttributeOrDefaultStubFactoryConfigurator;
use Ingenerator\StubObjects\Configurator\AttributeOrGuessStubAsConfigurator;
use Ingenerator\StubObjects\Configurator\AttributeOrGuessStubDefaultConfigurator;
use Ingenerator\StubObjects\Configurator\StubFactoryConfigurator;
use Ingenerator\StubObjects\Factory\StubFactoryImplementation;
use ReflectionClass;
use Throwable;

class StubObjects
{
    private readonly StubbingContext $context;
    private readonly StubFactoryConfigurator $factory_config;

    /**
     * @var array<string, StubFactoryImplementation>
     */
    private array $factory_cache = [];

    public function __construct(
        private readonly array $stubbable_class_patterns,
        ?StubFactoryConfigurator $factory_config = NULL,
    ) {
        $this->context = new StubbingContext($this, $this->stubbable_class_patterns);
        $this->factory_config = $factory_config ?? new AttributeOrDefaultStubFactoryConfigurator(
            new AttributeOrGuessStubDefaultConfigurator(StandardConfig::loadDefaultValueGuessers()),
            new AttributeOrGuessStubAsConfigurator(StandardConfig::loadCasterGuessers()),
        );
        // @todo: support passing in explicit factories for classes that can't be tagged
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
            $this->factory_cache[$class] ??= $this->makeFactory($class);

            return $this->factory_cache[$class]->make($values, $this->context);
        } catch (Throwable $e) {
            throw new FailedToStubObjectException(
                sprintf('Could not stub a %s: [%s] %s', $class, $e::class, $e->getMessage()),
                previous: $e,
            );
        }
    }

    private function makeFactory(string $class): Factory\StubFactoryImplementation
    {
        $reflection = new ReflectionClass($class);

        return $this->factory_config->getStubFactory($reflection);
    }
}
