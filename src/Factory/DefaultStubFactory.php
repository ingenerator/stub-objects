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
use Ingenerator\StubObjects\StubbingContext;
use ReflectionClass;
use ReflectionProperty;

class DefaultStubFactory implements StubFactoryImplementation
{
    private const int FILTER_INSTANCE_PROPERTIES = ReflectionProperty::IS_PUBLIC
                                                   | ReflectionProperty::IS_PROTECTED
                                                   | ReflectionProperty::IS_PRIVATE;

    /**
     * @var array<string,StubDefault>
     */
    private array $stub_default_cache = [];

    /**
     * @var array<string,StubAs>
     */
    private array $stub_as_cache = [];

    private StubMergeDefaultsWith $defaults_merger;

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
        $values = $this->getDefaultsMerger()->mergeDefaults($defaults, $values);

        // Create the instance and apply customised values to it
        $instance = $this->target_reflection->newInstance();
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

            if ($prop->hasDefaultValue()) {
                // We'll just use the one defined in the class
                continue;
            }

            // Find the StubDefault provider for this property and cache it for re-use on other objects
            $this->stub_default_cache[$prop_name] ??= $this->default_vals->getDefaultValueProvider($prop, $context);

            $defaults[$prop_name] = $this->stub_default_cache[$prop_name]->getValue($values);
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


    private function getDefaultsMerger(): StubMergeDefaultsWith
    {
        if ( ! isset($this->defaults_merger)) {
            $custom_merger = $this->target_reflection->getAttributes(StubMergeDefaultsWith::class);
            if ($custom_merger) {
                $this->defaults_merger = $custom_merger[0]->newInstance();
            } else {
                // @todo: should this be a straight merge or a deep merge?
                // I think probably a straight merge, the only place it matters is child objects and for those we should
                // be explicit about which level they are set from
                $this->defaults_merger = new StubMergeDefaultsWith(
                    fn(array $defaults, array $values) => [...$defaults, ...$values]
                );
            }
        }

        return $this->defaults_merger;
    }

}
