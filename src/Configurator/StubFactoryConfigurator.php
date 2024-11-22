<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Configurator;

use Ingenerator\StubObjects\Factory\StubFactoryImplementation;
use ReflectionClass;

interface StubFactoryConfigurator
{

    public function getStubFactory(ReflectionClass $class): StubFactoryImplementation;

}
