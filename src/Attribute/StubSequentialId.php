<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class StubSequentialId implements DefaultStubValueProvider
{
    private static int $next_id = 1;

    public function getValue(array $specified_values): int
    {
        return ++self::$next_id;
    }

}
