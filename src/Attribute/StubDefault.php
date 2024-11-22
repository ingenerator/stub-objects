<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute;

interface StubDefault
{
    public function getValue(array $specified_values): mixed;
}
