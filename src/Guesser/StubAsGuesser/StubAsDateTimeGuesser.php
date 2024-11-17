<?php

namespace Ingenerator\StubObjects\Guesser\StubAsGuesser;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\Attribute\StubAs\StubAsDateTime;
use Ingenerator\StubObjects\Guesser\StubAsGuesser;
use ReflectionProperty;

class StubAsDateTimeGuesser implements StubAsGuesser
{
    public function guessCaster(ReflectionProperty $property): false|StubAs
    {
        if ($property->getType()->getName() === DateTimeImmutable::class) {
            return new StubAsDateTime();
        }

        return FALSE;
    }

}
