<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Factory;

use Ingenerator\StubObjects\StubbingContext;

interface StubFactoryImplementation
{
    public function make(array $values, StubbingContext $context): object;

}
