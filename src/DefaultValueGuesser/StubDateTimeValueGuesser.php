<?php

namespace Ingenerator\StubObjects\DefaultValueGuesser;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\DefaultStubValueProvider;
use Ingenerator\StubObjects\Attribute\StubDefaultValue;
use ReflectionProperty;

class StubDateTimeValueGuesser implements DefaultValueProviderGuesser
{
    public function guessProvider(ReflectionProperty $property): false|DefaultStubValueProvider
    {
        if ($property->getType()->getName() === DateTimeImmutable::class) {
            // Note that we're guessing this in string form, converting back to a DateTime happens at the
            // point of hydrating values because we anyway need to do it there for values that came in overrides
            return new StubDefaultValue('now');
        }

        return FALSE;
    }


}
