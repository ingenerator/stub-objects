<?php

namespace Ingenerator\StubObjects\Attribute\StubAs;

use Attribute;
use BackedEnum;
use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\StubbingContext;

#[Attribute(Attribute::TARGET_PROPERTY)]
class StubAsBackedEnum implements StubAs
{
    /**
     * @param class-string<BackedEnum> $as_class
     */
    public function __construct(
        private readonly string $as_class
    ) {

    }

    public function cast(string $property, mixed $value, StubbingContext $context): mixed
    {
        if (($value === NULL) || ($value instanceof BackedEnum)) {
            // Note, we return the original even if it's not the expected type, it will make more sense to get an
            // exception on attempting to assign this to the property than to have the complexity of checking and
            // throwing here.
            return $value;
        }

        // Convert it to the backed enum
        return call_user_func([$this->as_class, 'from'], $value);
    }

}
