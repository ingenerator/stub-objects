<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute;

use Attribute;
use Ingenerator\StubObjects\Factory\CallableStubFactory;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class StubFactory
{
    public CallableStubFactory $factory;

    public function __construct(
        callable $factory,
    ) {
        // We can't use the first-class callable syntax in an attribute definition, so we have to pass
        // legacy callables (e.g. [SomeClass::class, 'methodToCall']) and convert them here
        $this->factory = new CallableStubFactory($factory(...));
    }

    public function getFactory(): CallableStubFactory
    {
        return $this->factory;
    }
}
