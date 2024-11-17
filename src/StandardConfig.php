<?php

namespace Ingenerator\StubObjects;

use Ingenerator\StubObjects\CasterGuesser\CasterGuesser;
use Ingenerator\StubObjects\CasterGuesser\StubAsDateTimeGuesser;
use Ingenerator\StubObjects\DefaultValueGuesser\DefaultValueProviderGuesser;
use Ingenerator\StubObjects\DefaultValueGuesser\StubbableObjectValueGuesser;
use Ingenerator\StubObjects\DefaultValueGuesser\StubDateTimeValueGuesser;
use Ingenerator\StubObjects\DefaultValueGuesser\StubNullValueGuesser;
use Ingenerator\StubObjects\DefaultValueGuesser\StubStringValueGuesser;

class StandardConfig
{

    /**
     * @return CasterGuesser[]
     */
    public static function loadCasterGuessers():array
    {
        return [
            new StubAsDateTimeGuesser(),
        ];
    }

    /**
     * @return DefaultValueProviderGuesser[]
     */
    public static function loadDefaultValueGuessers(): array
    {
        // @todo: I *think* we only want to load these once?
        return [
            new StubNullValueGuesser(),
            new StubDateTimeValueGuesser(),
            new StubStringValueGuesser(),
            new StubbableObjectValueGuesser(),
        ];
    }
}