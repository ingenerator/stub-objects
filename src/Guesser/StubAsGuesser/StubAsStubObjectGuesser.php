<?php

namespace Ingenerator\StubObjects\Guesser\StubAsGuesser;

use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\Attribute\StubAs\StubAsStubObject;
use Ingenerator\StubObjects\Guesser\StubAsGuesser;
use Ingenerator\StubObjects\ReflectionUtils;
use Ingenerator\StubObjects\StubbingContext;
use ReflectionProperty;

class StubAsStubObjectGuesser implements StubAsGuesser
{
    public function guessCaster(ReflectionProperty $property, StubbingContext $context): false|StubAs
    {
        if (ReflectionUtils::isBuiltinType($property)) {
            return FALSE;
        }

        $class = ReflectionUtils::getTypeNameIfAvailable($property);
        if ($class && $context->isStubbable($class)) {
            return new StubAsStubObject($class);
        }

        return FALSE;
    }

}
