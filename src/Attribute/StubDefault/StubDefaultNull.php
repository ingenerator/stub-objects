<?php

namespace Ingenerator\StubObjects\Attribute\StubDefault;

use Ingenerator\StubObjects\Attribute\StubDefault;

class StubDefaultNull implements StubDefault
{
    public function getValue(array $specified_values): mixed
    {
        return NULL;
    }

}
