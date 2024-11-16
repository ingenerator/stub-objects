<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects;

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

            return $reflection->newInstance();
        } catch (Throwable $e) {
            throw new FailedToStubObjectException(
                sprintf('Could not stub a %s: [%s] %s', $class, $e::class, $e->getMessage()),
                previous: $e,
            );
        }
    }
}
