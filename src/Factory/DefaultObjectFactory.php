<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Factory;

use ReflectionClass;
use ReflectionProperty;

class DefaultObjectFactory
{
    private const int FILTER_INSTANCE_PROPERTIES = ReflectionProperty::IS_PUBLIC
                                                   | ReflectionProperty::IS_PROTECTED
                                                   | ReflectionProperty::IS_PRIVATE;

    public function __construct(
        private readonly ReflectionClass $target_reflection
    ) {

    }

    public function make(array $values): object
    {
        $values = $this->mergeDefaultValues($values);

        // Create the instance and apply customised values to it
        $instance = $this->target_reflection->newInstance();
        foreach ($values as $prop_name => $value) {
            $property = $this->target_reflection->getProperty($prop_name);
            $property->setValue($instance, $value);
        }

        return $instance;
    }

    private function mergeDefaultValues(array $values): array
    {
        foreach ($this->target_reflection->getProperties(self::FILTER_INSTANCE_PROPERTIES) as $prop) {
            $prop_name = $prop->getName();
            if ($prop->hasDefaultValue()) {
                // We'll just use the one defined in the class
                continue;
            }

            if (array_key_exists($prop_name, $values)) {
                // The caller has specified a value
                continue;
            }

            $values[$prop_name] = $this->stubDefaultValue($prop);
        }

        return $values;
    }

    private function stubDefaultValue(ReflectionProperty $prop): mixed
    {
        if ($prop->getType()->allowsNull()) {
            // If the property type is nullable, assume it can be defaulted to a null
            return NULL;
        }
    }


}
