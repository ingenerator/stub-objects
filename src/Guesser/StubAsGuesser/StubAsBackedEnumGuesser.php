<?php

namespace Ingenerator\StubObjects\Guesser\StubAsGuesser;

use BackedEnum;
use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\Attribute\StubAs\StubAsBackedEnum;
use Ingenerator\StubObjects\Guesser\StubAsGuesser;
use Ingenerator\StubObjects\ReflectionUtils;
use Ingenerator\StubObjects\StubbingContext;
use ReflectionProperty;

class StubAsBackedEnumGuesser implements StubAsGuesser
{
    public function guessCaster(ReflectionProperty $property, StubbingContext $context): false|StubAs
    {
        if (ReflectionUtils::isBuiltinType($property)) {
            return FALSE;
        }

        $type = ReflectionUtils::getTypeNameIfAvailable($property);
        if ($type && is_a($type, BackedEnum::class, TRUE)) {
            return new StubAsBackedEnum($type);
        }

        return FALSE;
    }

}
