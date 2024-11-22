<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Configurator;

use Ingenerator\StubObjects\Attribute\StubDefault;
use Ingenerator\StubObjects\StubbingContext;
use ReflectionProperty;

interface StubDefaultConfigurator
{
    public function getDefaultValueProvider(ReflectionProperty $property, StubbingContext $context): false|StubDefault;

}
