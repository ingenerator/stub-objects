<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute;

interface DefaultStubValueProvider
{
    public function getValue(): mixed;
}
