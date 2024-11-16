<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Factory;

use Ingenerator\StubObjects\Configurator\AttributeOrGuessingDefaultValueConfigurator;
use Ingenerator\StubObjects\Configurator\AttributeOrGuessingValueCasterConfigurator;
use Ingenerator\StubObjects\Configurator\DefaultValueConfigurator;
use Ingenerator\StubObjects\Configurator\ValueCasterConfigurator;
use Ingenerator\StubObjects\DefaultValueProvider\AttributeBasedDefaultValueProvider;
use Ingenerator\StubObjects\DefaultValueProvider\DefaultValueProviderImplementation;
use ReflectionClass;
use ReflectionProperty;

class DefaultObjectFactory implements StubFactoryImplementation
{
    private const int FILTER_INSTANCE_PROPERTIES = ReflectionProperty::IS_PUBLIC
                                                   | ReflectionProperty::IS_PROTECTED
                                                   | ReflectionProperty::IS_PRIVATE;

    public function __construct(
        private readonly ReflectionClass $target_reflection,
        private readonly DefaultValueConfigurator $default_vals = new AttributeOrGuessingDefaultValueConfigurator(),
        private readonly ValueCasterConfigurator $value_casters = new AttributeOrGuessingValueCasterConfigurator(),
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
