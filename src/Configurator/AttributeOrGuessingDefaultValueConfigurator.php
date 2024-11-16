<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Configurator;

use DomainException;
use Ingenerator\StubObjects\Attribute\DefaultStubValueProvider;
use Ingenerator\StubObjects\DefaultValueGuesser\DefaultValueProviderGuesser;
use Ingenerator\StubObjects\StandardConfig;
use ReflectionAttribute;
use ReflectionProperty;

class AttributeOrGuessingDefaultValueConfigurator implements DefaultValueConfigurator
{
    /**
     * @var DefaultValueProviderGuesser[]
     */
    private readonly array $guessers;

    public function __construct(?array $guessers = NULL)
    {
        $this->guessers = $guessers ?? StandardConfig::loadDefaultValueGuessers();
    }

    public function getDefaultValueProvider(ReflectionProperty $property): DefaultStubValueProvider
    {
        $attrs = $property->getAttributes(DefaultStubValueProvider::class, ReflectionAttribute::IS_INSTANCEOF);
        if ($attrs) {
            return $this->returnSingleProviderFromAttributes($attrs, $property);
        }

        foreach ($this->guessers as $guesser) {
            if ($provider = $guesser->guessProvider($property)) {
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
