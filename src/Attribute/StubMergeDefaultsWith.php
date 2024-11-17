<?php

namespace Ingenerator\StubObjects\Attribute;

use Attribute;
use Closure;

#[Attribute(Attribute::TARGET_CLASS)]
class StubMergeDefaultsWith
{
    private Closure $factory;

    public function __construct(
        callable $factory,
    ) {
        // We can't use the first-class callable syntax in an attribute definition, so we have to pass
        // legacy callables (e.g. [SomeClass::class, 'methodToCall']) and convert them here
        $this->factory = $factory(...);
    }

    public function mergeDefaults(array $defaults, array $values): array
    {
        return $this->factory->__invoke(defaults: $defaults, values: $values);
    }

}
