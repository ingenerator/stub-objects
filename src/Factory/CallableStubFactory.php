<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Factory;

use Closure;
use Ingenerator\StubObjects\StubbingContext;

class CallableStubFactory implements StubFactoryImplementation
{
    public function __construct(private readonly Closure $factory) { }

    public function make(array $values, StubbingContext $context): object
    {
        return $this->factory->__invoke($values);
    }


}
