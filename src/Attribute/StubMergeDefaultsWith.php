<?php

namespace Ingenerator\StubObjects\Attribute;

use Attribute;
use Ingenerator\StubObjects\StubFinalValuesMerger;

#[Attribute(Attribute::TARGET_CLASS)]
class StubMergeDefaultsWith
{

    public function __construct(public readonly StubFinalValuesMerger $merger)
    {
    }

}
