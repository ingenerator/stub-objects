<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Configurator;

use Ingenerator\StubObjects\Attribute\StubValueCaster;
use ReflectionProperty;

interface ValueCasterConfigurator
{
    public function getCaster(ReflectionProperty $property): false|StubValueCaster;
}
