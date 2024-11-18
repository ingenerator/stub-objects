<?php

namespace Ingenerator\StubObjects\Guesser\StubAsGuesser;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\Attribute\StubAs\StubAsDateTime;
use Ingenerator\StubObjects\Guesser\StubAsGuesser;
use Ingenerator\StubObjects\StubbingContext;
use ReflectionProperty;

class StubAsDateTimeGuesser implements StubAsGuesser
{
    public function guessCaster(ReflectionProperty $property, StubbingContext $context): false|StubAs
    {
        // @todo test with untyped props
        if ($property->getType()?->getName() === DateTimeImmutable::class) {
            return new StubAsDateTime();
        }

        return FALSE;
    }

}
