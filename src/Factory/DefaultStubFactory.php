<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Factory;

use Ingenerator\StubObjects\Configurator\AttributeOrGuessStubAsConfigurator;
use Ingenerator\StubObjects\Configurator\AttributeOrGuessStubDefaultConfigurator;
use Ingenerator\StubObjects\Configurator\StubAsConfigurator;
use Ingenerator\StubObjects\Configurator\StubDefaultConfigurator;
use Ingenerator\StubObjects\DefaultValueProvider\AttributeBasedDefaultValueProvider;
use Ingenerator\StubObjects\DefaultValueProvider\DefaultValueProviderImplementation;
use ReflectionClass;
use ReflectionProperty;

class DefaultStubFactory implements StubFactoryImplementation
{
    private const int FILTER_INSTANCE_PROPERTIES = ReflectionProperty::IS_PUBLIC
                                                   | ReflectionProperty::IS_PROTECTED
                                                   | ReflectionProperty::IS_PRIVATE;

    public function __construct(
        private readonly ReflectionClass $target_reflection,
        private readonly StubDefaultConfigurator $default_vals = new AttributeOrGuessStubDefaultConfigurator(),
        private readonly StubAsConfigurator $value_casters = new AttributeOrGuessStubAsConfigurator(),
    ) {

    }

    public function make(array $values): object
    {
        $defaults = $this->getDefaultsForUnspecifiedProperties($values);
        $values = [...$defaults, ...$values];

        // Create the instance and apply customised values to it
        $instance = $this->target_reflection->newInstance();
        foreach ($values as $prop_name => $value) {
            $property = $this->target_reflection->getProperty($prop_name);
            // @todo: splitting getting the $caster here is again because this should be cacheable for a class
            $caster = $this->value_casters->getCaster($property);
            if ($caster) {
                $value = $caster->cast($prop_name, $value);
            }

            $property->setValue($instance, $value);
        }

        return $instance;
    }

    private function getDefaultsForUnspecifiedProperties(array $values): array
    {
        $defaults = [];
        foreach ($this->target_reflection->getProperties(self::FILTER_INSTANCE_PROPERTIES) as $prop) {
            $prop_name = $prop->getName();

            if (array_key_exists($prop_name, $values)) {
                // The caller has specified a value
                continue;
            }

            if ($prop->hasDefaultValue()) {
                // We'll just use the one defined in the class
                continue;
            }

            // @todo this chaining is because we can probably cache the getDefaultValueProviders for each class
            $defaults[$prop_name] = $this->default_vals->getDefaultValueProvider($prop)->getValue([]);
        }

        return $defaults;
    }

}
