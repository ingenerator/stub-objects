<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects;

use Ingenerator\StubObjects\Attribute\StubFactory;
use Ingenerator\StubObjects\Factory\DefaultObjectFactory;
use ReflectionClass;
use Throwable;

class StubObjectFactory
{

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
            $factory = $this->getFactoryMapping($reflection);

            return $factory->make($values);
        } catch (Throwable $e) {
            throw new FailedToStubObjectException(
                sprintf('Could not stub a %s: [%s] %s', $class, $e::class, $e->getMessage()),
                previous: $e,
            );
        }
    }

    private function getFactoryMapping(ReflectionClass $reflection): StubFactory
    {
        $attrs = $reflection->getAttributes(StubFactory::class);
        // @todo throw if multiple
        if ($attrs) {
            return $attrs[0]->newInstance();
        }

        return new StubFactory(new DefaultObjectFactory($reflection));
    }
}
