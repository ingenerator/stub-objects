<?php

namespace Ingenerator\StubObjects\Guesser\StubDefaultGuesser;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubDefault;
use Ingenerator\StubObjects\Attribute\StubDefault\StubDefaultValue;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser;
use Ingenerator\StubObjects\ReflectionUtils;
use Ingenerator\StubObjects\StubbingContext;
use ReflectionProperty;

class StubDefaultDateTimeGuesser implements StubDefaultGuesser
{
    public function guessProvider(ReflectionProperty $property, StubbingContext $context): false|StubDefault
    {
        if (ReflectionUtils::getTypeNameIfAvailable($property) === DateTimeImmutable::class) {
            // Note that we're guessing this in string form, converting back to a DateTime happens at the
            // point of hydrating values because we anyway need to do it there for values that came in overrides
            return new StubDefaultValue('now');
        }

        return FALSE;
    }


}
