<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Configurator;

use Ingenerator\StubObjects\Attribute\StubDefault;
use ReflectionProperty;

interface StubDefaultConfigurator
{
    public function getDefaultValueProvider(ReflectionProperty $property): StubDefault;

}
