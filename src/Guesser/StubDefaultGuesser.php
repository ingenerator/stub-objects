<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Guesser;

use Ingenerator\StubObjects\Attribute\StubDefault;
use Ingenerator\StubObjects\StubbingContext;
use ReflectionProperty;

interface StubDefaultGuesser
{
    public function guessProvider(ReflectionProperty $property, StubbingContext $context): false|StubDefault;

}
