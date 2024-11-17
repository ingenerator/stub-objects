<?php
declare(strict_types=1);

namespace Ingenerator\StubObjects\Configurator;

use Ingenerator\StubObjects\Attribute\StubFactory;
use Ingenerator\StubObjects\Factory\DefaultStubFactory;
use Ingenerator\StubObjects\Factory\StubFactoryImplementation;
use ReflectionClass;

class AttributeOrDefaultStubFactoryConfigurator implements StubFactoryConfigurator
{
    public function __construct(
        private readonly StubDefaultConfigurator $default_configurator,
        private readonly StubAsConfigurator $stub_as_configurator,
    ) {

    }

    public function getStubFactory(ReflectionClass $class): StubFactoryImplementation
    {
        $attrs = $class->getAttributes(StubFactory::class);
        if ($attrs) {
            return $attrs[0]->newInstance()->getFactory();
        }

        return new DefaultStubFactory(
            $class,
            $this->default_configurator,
            $this->stub_as_configurator,
        );
    }

}
