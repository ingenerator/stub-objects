<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Configurator;

use DomainException;
use Ingenerator\StubObjects\Attribute\StubValueCaster;
use ReflectionAttribute;
use ReflectionProperty;

class AttributeOrGuessingValueCasterConfigurator implements ValueCasterConfigurator
{
    public function getCaster(ReflectionProperty $property): false|StubValueCaster
    {
        $attrs = $property->getAttributes(StubValueCaster::class, ReflectionAttribute::IS_INSTANCEOF);
        if ($attrs) {
            return $this->returnSingleProviderFromAttributes($attrs, $property);
        }

        return FALSE;
    }


    private function returnSingleProviderFromAttributes(array $attrs, ReflectionProperty $property): StubValueCaster
    {
        if (count($attrs) > 1) {
            throw new DomainException(
                sprintf(
                    'Property %s should have only one StubValueCaster attribute, got %d',
                    $property->getName(),
                    count($attrs)
                )
            );
        }

        return $attrs[0]->newInstance();
    }

}
