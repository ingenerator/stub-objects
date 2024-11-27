<?php

namespace Ingenerator\StubObjects\Guesser\StubAsGuesser;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\Attribute\StubAs\StubAsDateTime;
use Ingenerator\StubObjects\Guesser\StubAsGuesser;
use Ingenerator\StubObjects\ReflectionUtils;
use Ingenerator\StubObjects\StubbingContext;
use ReflectionProperty;

class StubAsDateTimeGuesser implements StubAsGuesser
{
    public function guessCaster(ReflectionProperty $property, StubbingContext $context): false|StubAs
    {
        if (ReflectionUtils::getTypeNameIfAvailable($property) === DateTimeImmutable::class) {
            return new StubAsDateTime();
        }

        return FALSE;
    }

}
