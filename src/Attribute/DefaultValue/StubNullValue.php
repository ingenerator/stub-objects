<?php

namespace Ingenerator\StubObjects\Attribute\DefaultValue;

use Ingenerator\StubObjects\Attribute\DefaultStubValueProvider;

class StubNullValue implements DefaultStubValueProvider
{
    public function getValue(array $specified_values): mixed
    {
        return NULL;
    }

}
