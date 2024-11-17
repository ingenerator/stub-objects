<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute\StubAs;

use Attribute;
use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubAs;

#[Attribute(Attribute::TARGET_PROPERTY)]
class StubAsDateTime implements StubAs
{
    public function cast(string $property, mixed $value): mixed
    {
        // @todo sanity checks for the potential types of input
        // @todo support our DateParam syntax
        if (($value === NULL) || ($value instanceof DateTimeImmutable)) {
            return $value;
        }

        return new DateTimeImmutable($value);
    }

}
