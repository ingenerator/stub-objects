<?php

namespace Ingenerator\StubObjects\Merger;

use Ingenerator\StubObjects\StubbingContext;
use Ingenerator\StubObjects\StubFinalValuesMerger;

class DefaultStubFinalValuesMerger implements StubFinalValuesMerger
{
    public function merge(array $defaults, array $values, StubbingContext $context): array
    {
        // This is intentionally just a straight (e.g. not recursive) array merge.
        // The only place a recursive merge would be relevant would be if an object was the owner of other objects. But
        // in that case, the owned objects should take their values from their own defaults + anything the caller
        // specified explicitly. Attempting to combine a third set of values from an arbitrary owning object's defaults
        // will just cause confusion.
        return [...$defaults, ...$values];
    }

}
