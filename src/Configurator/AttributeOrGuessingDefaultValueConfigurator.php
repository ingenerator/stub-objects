<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Configurator;

use DomainException;
use Ingenerator\StubObjects\Attribute\DefaultStubValueProvider;
use Ingenerator\StubObjects\Attribute\StubNullValue;
use ReflectionAttribute;
use ReflectionProperty;

class AttributeOrGuessingDefaultValueConfigurator implements DefaultValueConfigurator
{
    public function getDefaultValueProvider(ReflectionProperty $property): DefaultStubValueProvider
    {
        $attrs = $property->getAttributes(DefaultStubValueProvider::class, ReflectionAttribute::IS_INSTANCEOF);
        if ($attrs) {
            return $this->returnSingleProviderFromAttributes($attrs, $property);
        }

        if ($property->getType()->allowsNull()) {
            return new StubNullValue();
        }

    }

    private function returnSingleProviderFromAttributes(array $attrs, ReflectionProperty $property)
    {
        if (count($attrs) > 1) {
            throw new DomainException(
                sprintf(
                    'Property %s should have only one DefaultValueProvider attribute, got %d',
                    $property->getName(),
                    count($attrs)
                )
            );
        }

        return $attrs[0]->newInstance();
    }


}
