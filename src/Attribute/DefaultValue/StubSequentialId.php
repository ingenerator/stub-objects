<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute\DefaultValue;

use Attribute;
use Ingenerator\StubObjects\Attribute\DefaultStubValueProvider;

#[Attribute(Attribute::TARGET_PROPERTY)]
class StubSequentialId implements DefaultStubValueProvider
{
    private static int $next_id = 1;

    public function getValue(array $specified_values): int
    {
        return ++self::$next_id;
    }

}
