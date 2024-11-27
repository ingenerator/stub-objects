<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Factory;

use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\Attribute\StubDefault;
use Ingenerator\StubObjects\Attribute\StubMergeDefaultsWith;
use Ingenerator\StubObjects\Configurator\StubAsConfigurator;
use Ingenerator\StubObjects\Configurator\StubDefaultConfigurator;
use Ingenerator\StubObjects\DefaultValueProvider\AttributeBasedDefaultValueProvider;
use Ingenerator\StubObjects\DefaultValueProvider\DefaultValueProviderImplementation;
use Ingenerator\StubObjects\Merger\DefaultStubFinalValuesMerger;
use Ingenerator\StubObjects\StubbingContext;
use Ingenerator\StubObjects\StubFinalValuesMerger;
use ReflectionClass;
use ReflectionProperty;

class DefaultStubFactory implements StubFactoryImplementation
{
    private const int FILTER_INSTANCE_PROPERTIES = ReflectionProperty::IS_PUBLIC
                                                   | ReflectionProperty::IS_PROTECTED
                                                   | ReflectionProperty::IS_PRIVATE;

    /**
     * @var array<string,StubDefault|false>
     */
    private array $stub_default_cache = [];

    /**
     * @var array<string,StubAs>
     */
    private array $stub_as_cache = [];

    private StubFinalValuesMerger $defaults_merger;

    public function __construct(
        private readonly ReflectionClass $target_reflection,
        private readonly StubDefaultConfigurator $default_vals,
        private readonly StubAsConfigurator $value_casters,
    ) {

    }

    public function make(array $values, StubbingContext $context): object
    {
        $defaults = $this->getDefaultsForUnspecifiedProperties($values, $context);

        // Merge the defaults together (optionally using the custom merger for the class)
        $values = $this->getDefaultsMerger()->merge($defaults, $values, $context);

        // Create the instance and apply customised values to it
        $instance = $this->target_reflection->newInstanceWithoutConstructor();
        foreach ($values as $prop_name => $value) {
            $this->castAndSetPropertyValue($prop_name, $context, $value, $instance);
        }

        return $instance;
    }

    private function getDefaultsForUnspecifiedProperties(array $values, StubbingContext $context): array
    {
        $defaults = [];
        foreach ($this->target_reflection->getProperties(self::FILTER_INSTANCE_PROPERTIES) as $prop) {
            $prop_name = $prop->getName();

            if (array_key_exists($prop_name, $values)) {
                // The caller has specified a value
                continue;
            }

            $default_provider = $this->findAndCacheDefaultProvider($prop_name, $prop, $context);
            if ($default_provider) {
                // Either the property has an explicit StubDefault attribute, or it has no default value in the class
                // itself but we have been able to guess a suitable provider.
                $defaults[$prop_name] = $default_provider->getValue($values);
            }
        }

        return $defaults;
    }

    private function castAndSetPropertyValue(
        string $prop_name,
        StubbingContext $context,
        mixed $value,
        object $instance
    ): void {
        $property = $this->target_reflection->getProperty($prop_name);

        // Load and cache the caster for this property (which may be `false`)
        // Then cast the value to the expected type
        $this->stub_as_cache[$prop_name] ??= $this->value_casters->getCaster($property, $context);
        if ($this->stub_as_cache[$prop_name]) {
            $value = $this->stub_as_cache[$prop_name]->cast($prop_name, $value, $context);
        }

        // And finally apply to the new object instance
        $property->setValue($instance, $value);
    }


    private function getDefaultsMerger(): StubFinalValuesMerger
    {
        if ( ! isset($this->merge_with)) {
            $attrs = $this->target_reflection->getAttributes(StubMergeDefaultsWith::class);
            if ($attrs) {
                $attr = $attrs[0]->newInstance();
                /** @var StubMergeDefaultsWith $attr */
                $this->defaults_merger = $attr->merger;
            } else {
                $this->defaults_merger = new DefaultStubFinalValuesMerger();
            }
        }

        return $this->defaults_merger;
    }

    private function findAndCacheDefaultProvider(
        string $prop_name,
        ReflectionProperty $prop,
        StubbingContext $context
    ): false|StubDefault {
        // Find the StubDefault provider for this property and cache it for re-use on other objects
        $this->stub_default_cache[$prop_name] ??= $this->default_vals->getDefaultValueProvider($prop, $context);

        return $this->stub_default_cache[$prop_name];
    }

}
