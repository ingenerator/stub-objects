<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute\StubAs;

use Attribute;
use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\StubbingContext;
use InvalidArgumentException;

#[Attribute(Attribute::TARGET_PROPERTY)]
class StubAsDateTime implements StubAs
{
    public function cast(string $property, mixed $value, StubbingContext $context): mixed
    {
        // @todo sanity checks for the potential types of input
        // @todo support our DateParam syntax
        if (($value === NULL) || ($value instanceof DateTimeImmutable)) {
            return $value;
        }

        if ($value === '') {
            throw new InvalidArgumentException('Cannot stub a DateTime value from an empty string');
        }

        return new DateTimeImmutable($value);
    }

}
