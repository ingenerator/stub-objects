<?php

namespace Ingenerator\StubObjects\Attribute\StubAs;

use Attribute;
use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\StubbingContext;

#[Attribute(Attribute::TARGET_PROPERTY)]
class StubAsJsonEncoded implements StubAs
{
    public function cast(string $property, mixed $value, StubbingContext $context): mixed
    {
        // @todo test this, also do we definitely want it in core?? And if we do would we ever guess it?
        if (($value === null) || is_string($value)) {
            return $value;
        }

        return json_encode($value, JSON_THROW_ON_ERROR);
    }

}