<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects;

use Ingenerator\StubObjects\Configurator\AttributeOrDefaultStubFactoryConfigurator;
use Ingenerator\StubObjects\Configurator\StubFactoryConfigurator;
use ReflectionClass;
use Throwable;

class StubObjects
{
    public function __construct(
        private readonly StubFactoryConfigurator $factory_config = new AttributeOrDefaultStubFactoryConfigurator(),
    ) {
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

            return $factory->make($values);
        } catch (Throwable $e) {
            throw new FailedToStubObjectException(
                sprintf('Could not stub a %s: [%s] %s', $class, $e::class, $e->getMessage()),
                previous: $e,
            );
        }
    }
}
