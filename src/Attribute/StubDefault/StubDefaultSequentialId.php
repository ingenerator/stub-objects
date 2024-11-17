<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute\StubDefault;

use Attribute;
use Ingenerator\StubObjects\Attribute\StubDefault;

#[Attribute(Attribute::TARGET_PROPERTY)]
class StubDefaultSequentialId implements StubDefault
{
    private static int $next_id = 1;

    public function getValue(array $specified_values): int
    {
        return ++self::$next_id;
    }

}
