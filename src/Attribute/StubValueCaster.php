<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute;

interface StubValueCaster
{
    public function cast(string $property, mixed $value): mixed;
}
