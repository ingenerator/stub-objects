<?php

namespace Ingenerator\StubObjects\Attribute\StubAs;

use Attribute;
use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\StubbingContext;

#[Attribute(Attribute::TARGET_PROPERTY)]
class StubAsStubObject implements StubAs
{
    public function __construct(private readonly string $as_class)
    {

    }

    public function cast(string $property, mixed $value, StubbingContext $context): mixed
    {
        // @todo: direct tests for the stubAs methods
        if (($value instanceof $this->as_class) || ($value === NULL)) {
            return $value;
        }

        return $context->stub_objects->stub($this->as_class, $value);
    }

}
