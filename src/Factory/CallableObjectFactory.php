<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Factory;

class CallableObjectFactory implements StubFactoryImplementation
{
    public function __construct(private readonly \Closure $factory) { }

    public function make(array $values): object
    {
        return $this->factory->__invoke($values);
    }


}
