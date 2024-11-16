<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Attribute;

use Attribute;
use Closure;
use Ingenerator\StubObjects\Factory\DefaultObjectFactory;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class StubFactory
{
    public Closure|DefaultObjectFactory $factory;

    public function __construct(
        callable|DefaultObjectFactory $factory,
    ) {

        $this->factory = match (TRUE) {
            $factory instanceof DefaultObjectFactory => $factory,
            // We can't use the first-class callable syntax in an attribute definition, so we have to pass
            // legacy callables (e.g. [SomeClass::class, 'methodToCall']) and convert them here
            default => $factory(...)
        };
    }

    public function make(array $values): mixed
    {
        if ($this->factory instanceof DefaultObjectFactory) {
            return $this->factory->make($values);
        }

        return ($this->factory)($values);
    }

}
