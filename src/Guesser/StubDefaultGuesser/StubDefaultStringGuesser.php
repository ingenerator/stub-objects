<?php

namespace Ingenerator\StubObjects\Guesser\StubDefaultGuesser;

use Ingenerator\StubObjects\Attribute\StubDefault;
use Ingenerator\StubObjects\Attribute\StubDefault\StubDefaultRandomString;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser;
use Ingenerator\StubObjects\StubbingContext;
use Random\Randomizer;
use ReflectionProperty;

class StubDefaultStringGuesser implements StubDefaultGuesser
{
    public function __construct(
        private Randomizer $randomizer = new Randomizer()
    ) {

    }

    public function guessProvider(ReflectionProperty $property, StubbingContext $context): false|StubDefault
    {
        if ($property->getType()->getName() === 'string') {
            // @todo: guess email addresses for props with `email` in the name
            // @todo: guess URLs for props with `url` in the name
            return new StubDefaultRandomString(
                length: 16,
                randomizer: $this->randomizer
            );
        }

        return FALSE;
    }


}
