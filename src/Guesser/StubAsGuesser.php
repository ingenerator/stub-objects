<?php

namespace Ingenerator\StubObjects\Guesser;

use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\StubbingContext;
use ReflectionProperty;

interface StubAsGuesser
{
    public function guessCaster(ReflectionProperty $property, StubbingContext $context): false|StubAs;

}
