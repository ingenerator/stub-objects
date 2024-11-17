<?php

namespace Ingenerator\StubObjects\CasterGuesser;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\Caster\StubAsDateTime;
use Ingenerator\StubObjects\Attribute\StubValueCaster;
use ReflectionProperty;

class StubAsDateTimeGuesser implements CasterGuesser
{
    public function guessCaster(ReflectionProperty $property): false|StubValueCaster
    {
        if ($property->getType()->getName() === DateTimeImmutable::class) {
            return new StubAsDateTime();
        }

        return FALSE;
    }

}
