<?php

namespace Ingenerator\StubObjects;

use Ingenerator\StubObjects\DefaultValueGuesser\DefaultValueProviderGuesser;
use Ingenerator\StubObjects\DefaultValueGuesser\StubDateTimeValueGuesser;
use Ingenerator\StubObjects\DefaultValueGuesser\StubNullValueGuesser;
use Ingenerator\StubObjects\DefaultValueGuesser\StubStringValueGuesser;

class StandardGuesserConfig
{

    /**
     * @return DefaultValueProviderGuesser[]
     */
    public static function loadGuessers(): array
    {
        // @todo: I *think* we only want to load these once?
        return [
            new StubNullValueGuesser(),
            new StubDateTimeValueGuesser(),
            new StubStringValueGuesser(),
        ];
    }
}
