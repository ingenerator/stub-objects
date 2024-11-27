<?php

namespace Ingenerator\StubObjects;

use Ingenerator\StubObjects\Guesser\StubAsGuesser;
use Ingenerator\StubObjects\Guesser\StubAsGuesser\StubAsBackedEnumGuesser;
use Ingenerator\StubObjects\Guesser\StubAsGuesser\StubAsCollectionGuesser;
use Ingenerator\StubObjects\Guesser\StubAsGuesser\StubAsDateTimeGuesser;
use Ingenerator\StubObjects\Guesser\StubAsGuesser\StubAsStubObjectGuesser;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser\StubDefaultDateTimeGuesser;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser\StubDefaultNullGuesser;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser\StubDefaultStringGuesser;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser\StubDefaultStubbableObjectGuesser;

class StandardConfig
{

    /**
     * @return StubAsGuesser[]
     */
    public static function loadCasterGuessers(): array
    {
        return [
            new StubAsDateTimeGuesser(),
            new StubAsStubObjectGuesser(),
            new StubAsCollectionGuesser(),
            new StubAsBackedEnumGuesser(),
        ];
    }

    /**
     * @return StubDefaultGuesser[]
     */
    public static function loadDefaultValueGuessers(): array
    {
        return [
            new StubDefaultNullGuesser(),
            new StubDefaultDateTimeGuesser(),
            new StubDefaultStringGuesser(),
            new StubDefaultStubbableObjectGuesser(),
        ];
    }
}
