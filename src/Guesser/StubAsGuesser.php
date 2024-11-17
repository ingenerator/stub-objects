<?php

namespace Ingenerator\StubObjects\Guesser;

use Ingenerator\StubObjects\Attribute\StubAs;
use ReflectionProperty;

interface StubAsGuesser
{
    public function guessCaster(ReflectionProperty $property): false|StubAs;

}
