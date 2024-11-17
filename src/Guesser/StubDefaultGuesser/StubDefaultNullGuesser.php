<?php

namespace Ingenerator\StubObjects\Guesser\StubDefaultGuesser;

use Ingenerator\StubObjects\Attribute\StubDefault;
use Ingenerator\StubObjects\Attribute\StubDefault\StubDefaultNull;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser;
use ReflectionProperty;

class StubDefaultNullGuesser implements StubDefaultGuesser
{
    public function guessProvider(ReflectionProperty $property): false|StubDefault
    {
        if ($property->getType()->allowsNull()) {
            return new StubDefaultNull();
        }

        return FALSE;
    }

}
