<?php

namespace Ingenerator\StubObjects;

use Ingenerator\StubObjects\DefaultValueGuesser\DefaultValueProviderGuesser;
use Ingenerator\StubObjects\DefaultValueGuesser\StubNullValueGuesser;

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
        ];
    }
}
