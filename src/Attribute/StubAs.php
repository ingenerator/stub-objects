<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute;

use Ingenerator\StubObjects\StubbingContext;

interface StubAs
{
    public function cast(string $property, mixed $value, StubbingContext $context): mixed;
}
