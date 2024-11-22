<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Configurator;

use DomainException;
use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\Guesser\StubAsGuesser;
use Ingenerator\StubObjects\StubbingContext;
use ReflectionAttribute;
use ReflectionProperty;

class AttributeOrGuessStubAsConfigurator implements StubAsConfigurator
{
    /**
     * @param StubAsGuesser[] $guessers
     */
    public function __construct(
        private readonly array $guessers,
    ) {
    }

    public function getCaster(ReflectionProperty $property, StubbingContext $context): false|StubAs
    {
        $attrs = $property->getAttributes(StubAs::class, ReflectionAttribute::IS_INSTANCEOF);
        if ($attrs) {
            return $this->returnSingleProviderFromAttributes($attrs, $property);
        }

        foreach ($this->guessers as $guesser) {
            if ($caster = $guesser->guessCaster($property, $context)) {
                return $caster;
            }
        }

        return FALSE;
    }


    private function returnSingleProviderFromAttributes(array $attrs, ReflectionProperty $property): StubAs
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
