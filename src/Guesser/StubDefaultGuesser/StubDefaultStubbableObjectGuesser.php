<?php

namespace Ingenerator\StubObjects\Guesser\StubDefaultGuesser;

use Ingenerator\StubObjects\Attribute\StubDefault;
use Ingenerator\StubObjects\Attribute\StubDefault\StubDefaultValue;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser;
use Ingenerator\StubObjects\StubbingContext;
use ReflectionProperty;

class StubDefaultStubbableObjectGuesser implements StubDefaultGuesser
{
    public function guessProvider(ReflectionProperty $property, StubbingContext $context): false|StubDefault
    {
        if ($property->getType()->isBuiltin()) {
            return FALSE;
        }

        if ( ! $context->isStubbable($property->getType()->getName())) {
            // Only set a default value for classes we expect to be able to stub, otherwise
            // users will get hard to trace "can't assign array to property of type ..."
            return FALSE;
        }

        // Note that we are defaulting to [] here to signify an empty object. Mapping that into
        // the object happens when we hydrate the stub, because the value from the user might also
        // be an array.
        return new StubDefaultValue([]);
    }


}
