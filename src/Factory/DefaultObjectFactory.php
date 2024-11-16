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
        // Create the instance and apply customised values to it
        $instance = $this->target_reflection->newInstance();
        foreach ($values as $prop_name => $value) {
            $property = $this->target_reflection->getProperty($prop_name);
            $property->setValue($instance, $value);
        }

        return $instance;
    }

}
