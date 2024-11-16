<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Factory;

use ReflectionClass;

class DefaultObjectFactory
{
    public function __construct(
        private readonly ReflectionClass $target_reflection
    ) {

    }

    public function make(array $values): object
    {
        return $this->target_reflection->newInstance();
    }
}
