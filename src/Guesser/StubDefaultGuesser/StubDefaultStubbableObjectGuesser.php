<?php

namespace Ingenerator\StubObjects\Guesser\StubDefaultGuesser;

use Ingenerator\StubObjects\Attribute\StubDefault;
use Ingenerator\StubObjects\Attribute\StubDefault\StubDefaultValue;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser;
use ReflectionProperty;

class StubDefaultStubbableObjectGuesser implements StubDefaultGuesser
{
    public function guessProvider(ReflectionProperty $property): false|StubDefault
    {
        if ($property->getType()->isBuiltin()) {
            return false;
        }

        // @todo this isn't safe like this, it will try and recurse into PHP internal classes too

        // Note that we are defaulting to [] here to signify an empty object. Mapping that into
        // the object happens when we hydrate the stub, because the value from the user might also
        // be an array.
        return new StubDefaultValue([]);
    }


}
