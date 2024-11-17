<?php

namespace Ingenerator\StubObjects;

use Ingenerator\StubObjects\Guesser\StubAsGuesser;
use Ingenerator\StubObjects\Guesser\StubAsGuesser\StubAsDateTimeGuesser;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser\StubDefaultNullGuesser;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser\StubDefaultStringGuesser;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser\StubDefaultStubbableObjectGuesser;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser\StubDefualtDateTimeGuesser;

class StandardConfig
{

    /**
     * @return StubAsGuesser[]
     */
    public static function loadCasterGuessers(): array
    {
        return [
            new StubAsDateTimeGuesser(),
        ];
    }

    /**
     * @return StubDefaultGuesser[]
     */
    public static function loadDefaultValueGuessers(): array
    {
        // @todo: I *think* we only want to load these once?
        return [
            new StubDefaultNullGuesser(),
            new StubDefualtDateTimeGuesser(),
            new StubDefaultStringGuesser(),
            new StubDefaultStubbableObjectGuesser(),
        ];
    }
}
