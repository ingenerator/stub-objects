<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Configurator;

use Ingenerator\StubObjects\Attribute\StubFactory;
use Ingenerator\StubObjects\Factory\DefaultObjectFactory;
use Ingenerator\StubObjects\Factory\StubFactoryImplementation;
use ReflectionClass;

class AttributeOrDefaultStubFactoryConfigurator implements StubFactoryConfigurator
{
    public function getStubFactory(ReflectionClass $class): StubFactoryImplementation
    {
        $attrs = $class->getAttributes(StubFactory::class);
        if ($attrs) {
            return $attrs[0]->newInstance()->getFactory();
        }

        return new DefaultObjectFactory($class);
    }

}
