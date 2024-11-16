<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute\Caster;

use Attribute;
use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubValueCaster;

#[Attribute(Attribute::TARGET_PROPERTY)]
class StubAsDateTime implements StubValueCaster
{
    public function cast(string $property, mixed $value): mixed
    {
        // @todo sanity checks for the potential types of input
        // @todo support our DateParam syntax
        if ($value instanceof DateTimeImmutable) {
            return $value;
        }

        return new DateTimeImmutable($value);
    }

}
