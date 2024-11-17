<?php

namespace Ingenerator\StubObjects\Guesser\StubAsGuesser;

use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\Attribute\StubAs\StubAsStubObject;
use Ingenerator\StubObjects\Guesser\StubAsGuesser;
use Ingenerator\StubObjects\StubbingContext;
use ReflectionProperty;

class StubAsStubObjectGuesser implements StubAsGuesser
{
    public function guessCaster(ReflectionProperty $property, StubbingContext $context): false|StubAs
    {
        if ($property->getType()->isBuiltin()) {
            return FALSE;
        }

        $class = $property->getType()->getName();
        if ($context->isStubbable($class)) {
            return new StubAsStubObject($class);
        }

        return FALSE;
    }

}