<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute\StubDefault;

use Attribute;
use Ingenerator\StubObjects\Attribute\StubDefault;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StubDefaultValue implements StubDefault
{
    public function __construct(private mixed $value)
    {

    }

    public function getValue(array $specified_values): mixed
    {
        return $this->value;
    }

}
