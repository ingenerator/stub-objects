<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Factory;

interface StubFactoryImplementation
{
    public function make(array $values): object;

}
