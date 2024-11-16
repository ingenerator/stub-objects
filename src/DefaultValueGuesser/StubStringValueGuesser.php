<?php

namespace Ingenerator\StubObjects\DefaultValueGuesser;

use Ingenerator\StubObjects\Attribute\DefaultStubValueProvider;
use Ingenerator\StubObjects\Attribute\DefaultValue\StubRandomString;
use Random\Randomizer;
use ReflectionProperty;

class StubStringValueGuesser implements DefaultValueProviderGuesser
{
    public function __construct(
        private Randomizer $randomizer = new Randomizer()
    ) {

    }

    public function guessProvider(ReflectionProperty $property): false|DefaultStubValueProvider
    {
        if ($property->getType()->getName() === 'string') {
            // @todo: guess email addresses for props with `email` in the name
            // @todo: guess URLs for props with `url` in the name
            return new StubRandomString(
                length: 16,
                randomizer: $this->randomizer
            );
        }

        return FALSE;
    }


}
