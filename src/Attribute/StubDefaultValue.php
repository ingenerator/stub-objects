<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StubDefaultValue implements DefaultStubValueProvider
{
    public function __construct(private string $value)
    {

    }

    public function getValue(): mixed
    {
        return $this->value;
    }

}
