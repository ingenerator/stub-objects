<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Configurator;

use DomainException;
use Ingenerator\StubObjects\Attribute\StubDefault;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser;
use Ingenerator\StubObjects\StubbingContext;
use ReflectionAttribute;
use ReflectionProperty;

class AttributeOrGuessStubDefaultConfigurator implements StubDefaultConfigurator
{
    /**
     * @param StubDefaultGuesser[] $guessers
     */
    public function __construct(
        private readonly array $guessers,
    ) {
    }

    public function getDefaultValueProvider(ReflectionProperty $property, StubbingContext $context): false|StubDefault
    {
        $attrs = $property->getAttributes(StubDefault::class, ReflectionAttribute::IS_INSTANCEOF);
        if ($attrs) {
            return $this->returnSingleProviderFromAttributes($attrs, $property);
        }

        if ($property->hasDefaultValue()) {
            // If there is a default on the class, we don't attempt to guess anything - the class will just be created
            // with its own default. This allows users to override e.g. a `?string $something = null` to have a value
            // by default when stubbing for tests, without that being a default at runtime.
            return false;
        }

        foreach ($this->guessers as $guesser) {
            if ($provider = $guesser->guessProvider($property, $context)) {
                return $provider;
            }
        }

        throw new DomainException(
            sprintf(
                'Could not guess a default value for `%s $%s` in %s - you will need to manually add a DefaultStubValueProvider attribute',
                $property->getType()->__toString(),
                $property->getName(),
                $property->getDeclaringClass()->getName(),
            )
        );
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
