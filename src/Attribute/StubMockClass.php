<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class StubMockClass
{

    public function __construct(public string $class)
    {

    }

}