<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Configurator;

use Ingenerator\StubObjects\Attribute\StubAs;
use ReflectionProperty;

interface StubAsConfigurator
{
    public function getCaster(ReflectionProperty $property): false|StubAs;
}
