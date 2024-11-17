<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Guesser;

use Ingenerator\StubObjects\Attribute\StubDefault;
use ReflectionProperty;

interface StubDefaultGuesser
{
    public function guessProvider(ReflectionProperty $property): false|StubDefault;

}
