<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\DefaultValueGuesser;

use Ingenerator\StubObjects\Attribute\DefaultStubValueProvider;
use ReflectionProperty;

interface DefaultValueProviderGuesser
{
    public function guessProvider(ReflectionProperty $property): ?DefaultStubValueProvider;

}
