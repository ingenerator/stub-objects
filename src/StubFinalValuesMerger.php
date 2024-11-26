<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects;

/**
 * Responsible for the final step of combining defaults with explicit values before the stub is hydrated
 */
interface StubFinalValuesMerger
{
    public function merge(array $defaults, array $values, StubbingContext $context): array;

}
