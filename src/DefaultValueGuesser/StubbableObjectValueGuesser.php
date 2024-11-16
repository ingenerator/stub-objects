<?php

namespace Ingenerator\StubObjects\DefaultValueGuesser;

use Ingenerator\StubObjects\Attribute\DefaultStubValueProvider;
use Ingenerator\StubObjects\Attribute\StubDefaultValue;
use ReflectionProperty;

class StubbableObjectValueGuesser implements DefaultValueProviderGuesser
{
    public function guessProvider(ReflectionProperty $property): false|DefaultStubValueProvider
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
