<?php

namespace Ingenerator\StubObjects\Guesser\StubAsGuesser;

use Doctrine\Common\Collections\Collection;
use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\Attribute\StubAs\StubAsCollection;
use Ingenerator\StubObjects\Guesser\StubAsGuesser;
use Ingenerator\StubObjects\StubbingContext;
use ReflectionProperty;

class StubAsCollectionGuesser implements StubAsGuesser
{
    public function guessCaster(ReflectionProperty $property, StubbingContext $context): false|StubAs
    {
        // @todo: test with untyped props
        if ($property->getType()?->getName() === Collection::class) {
            $collected_class = $this->parseItemClass($property);

            return new StubAsCollection($property->getType()->getName(), $collected_class);
        }

        return FALSE;
    }

    private function parseItemClass(ReflectionProperty $property): string
    {
        $doc = $property->getDocComment();
        if (preg_match('#@var\s+([^\s<]+)<([^>]+)>#', $doc, $matches)) {
            // @todo what if it's not an FQCN
            return $matches[2];
        }
        // @todo: throw
    }


}
