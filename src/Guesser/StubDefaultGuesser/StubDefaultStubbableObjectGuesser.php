<?php

namespace Ingenerator\StubObjects\Guesser\StubDefaultGuesser;

use Doctrine\Common\Collections\Collection;
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

        if ($this->isStubbable($context, $property)) {
            // Note that we are defaulting to [] here to signify an empty object. Mapping that into
            // the object happens when we hydrate the stub, because the value from the user might also
            // be an array.
            return new StubDefaultValue([]);
        }

        return FALSE;
    }

    private function isStubbable(StubbingContext $context, ReflectionProperty $property): bool
    {
        $type = $property->getType();
        if ($type->isBuiltin()) {
            return FALSE;
        }

        if ($type->getName() === Collection::class) {
            // Treat collections as a special case, we'll recursively attempt to cast arrays to collections
            return TRUE;
        }

        // Otherwise it depends if they've configured recursion
        return $context->isStubbable($property->getType()->getName());
    }

}
