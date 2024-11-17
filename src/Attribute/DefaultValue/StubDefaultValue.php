<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute\DefaultValue;

use Attribute;
use Ingenerator\StubObjects\Attribute\DefaultStubValueProvider;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StubDefaultValue implements DefaultStubValueProvider
{
    public function __construct(private mixed $value)
    {

    }

    public function getValue(array $specified_values): mixed
    {
        return $this->value;
    }

}