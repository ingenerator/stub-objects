<?php
declare(strict_types=1);

namespace test\unit\Configurator;

use Error;
use Ingenerator\StubObjects\Attribute\StubFactory;
use Ingenerator\StubObjects\Configurator\AttributeOrDefaultStubFactoryConfigurator;
use Ingenerator\StubObjects\Factory\CallableStubFactory;
use Ingenerator\StubObjects\Factory\DefaultStubFactory;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use test\TestUtils;

class AttributeOrDefaultStubFactoryConfiguratorTest extends TestCase
{
    public static function fakeMake(array $values): object
    {
        $values['_fake_make'] = TRUE;

        return (object) $values;
    }

    public function test_it_can_return_custom_factory_from_attribute()
    {
        $subject = $this->newSubject();
        $factory = $subject->getStubFactory(new ReflectionClass(MyEntityWithCustomFactory::class));
        $this->assertInstanceOf(CallableStubFactory::class, $factory);


        $result = $factory->make(['foo' => 'bar'], TestUtils::fakeStubbingContext());
        $this->assertSame(['foo' => 'bar', '_fake_make' => TRUE], (array) $result);
    }

    public function test_it_throws_if_multiple_custom_factories()
    {
        $subject = $this->newSubject();
        $this->expectException(Error::class);
        $this->expectExceptionMessage('StubFactory" must not be repeated');
        $subject->getStubFactory(new ReflectionClass(MyEntityWithTwoFactories::class));
    }

    public function test_it_returns_default_factory_if_none_specified()
    {
        $class = new class {
        };
        $factory = $this->newSubject()->getStubFactory(new ReflectionClass($class));
        $this->assertInstanceOf(DefaultStubFactory::class, $factory);
    }

    private function newSubject(): AttributeOrDefaultStubFactoryConfigurator
    {
        return new AttributeOrDefaultStubFactoryConfigurator();
    }

}


#[StubFactory([AttributeOrDefaultStubFactoryConfiguratorTest::class, 'fakeMake'])]
class MyEntityWithCustomFactory
{

}

#[StubFactory([AttributeOrDefaultStubFactoryConfiguratorTest::class, 'fakeMake'])]
#[StubFactory([AttributeOrDefaultStubFactoryConfiguratorTest::class, 'otherThing'])]
class MyEntityWithTwoFactories
{

}
