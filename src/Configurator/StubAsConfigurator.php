<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Configurator;

use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\StubbingContext;
use ReflectionProperty;

interface StubAsConfigurator
{
    public function getCaster(ReflectionProperty $property, StubbingContext $context): false|StubAs;
}
