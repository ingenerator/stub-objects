<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Configurator;

use Ingenerator\StubObjects\Attribute\DefaultStubValueProvider;
use ReflectionProperty;

interface DefaultValueConfigurator
{
    public function getDefaultValueProvider(ReflectionProperty $property): DefaultStubValueProvider;

}
