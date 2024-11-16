<?php

namespace Ingenerator\StubObjects\DefaultValueGuesser;

use Ingenerator\StubObjects\Attribute\DefaultStubValueProvider;
use Ingenerator\StubObjects\Attribute\StubNullValue;
use ReflectionProperty;

class StubNullValueGuesser implements DefaultValueProviderGuesser
{
    public function guessProvider(ReflectionProperty $property): false|DefaultStubValueProvider
    {
        if ($property->getType()->allowsNull()) {
            return new StubNullValue();
        }

        return FALSE;
    }

}
