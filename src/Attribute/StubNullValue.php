<?php

namespace Ingenerator\StubObjects\Attribute;

class StubNullValue implements DefaultStubValueProvider
{
    public function getValue(array $specified_values): mixed
    {
        return NULL;
    }

}
