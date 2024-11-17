<?php

namespace Ingenerator\StubObjects\CasterGuesser;

use Ingenerator\StubObjects\Attribute\StubValueCaster;
use ReflectionProperty;

interface CasterGuesser
{
    public function guessCaster(ReflectionProperty $property): false|StubValueCaster;

}
