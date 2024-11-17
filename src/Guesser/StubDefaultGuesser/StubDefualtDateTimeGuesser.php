<?php

namespace Ingenerator\StubObjects\Guesser\StubDefaultGuesser;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubDefault;
use Ingenerator\StubObjects\Attribute\StubDefault\StubDefaultValue;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser;
use ReflectionProperty;

class StubDefualtDateTimeGuesser implements StubDefaultGuesser
{
    public function guessProvider(ReflectionProperty $property): false|StubDefault
    {
        if ($property->getType()->getName() === DateTimeImmutable::class) {
            // Note that we're guessing this in string form, converting back to a DateTime happens at the
            // point of hydrating values because we anyway need to do it there for values that came in overrides
            return new StubDefaultValue('now');
        }

        return FALSE;
    }


}
