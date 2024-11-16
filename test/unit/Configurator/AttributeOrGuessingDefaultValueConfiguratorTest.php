<?php
declare(strict_types=1);

namespace test\unit\Ingenerator\StubObjects\Configurator;


use Attribute;
use DomainException;
use Ingenerator\StubObjects\Attribute\DefaultStubValueProvider;
use Ingenerator\StubObjects\Attribute\StubDefaultValue;
use Ingenerator\StubObjects\Attribute\StubNullValue;
use Ingenerator\StubObjects\Configurator\AttributeOrGuessingDefaultValueConfigurator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;

class AttributeOrGuessingDefaultValueConfiguratorTest extends TestCase
{

    public function test_it_returns_predefined_value_provider_attribute()
    {
        $class = new class {
            #[StubDefaultValue('whatever')]
            private readonly mixed $foo;
        };
        $provider = $this->newSubject()->getDefaultValueProvider($this->getReflectionProperty($class::class, 'foo'));
        $this->assertInstanceOf(StubDefaultValue::class, $provider);
        $this->assertSame('whatever', $provider->getValue([]));
    }

    public function test_it_returns_custom_value_provider_attribute()
    {
        $class = new class {
            #[MyCustomProvider([1, 923])]
            private readonly mixed $foo;
        };
        $provider = $this->newSubject()->getDefaultValueProvider($this->getReflectionProperty($class::class, 'foo'));
        $this->assertInstanceOf(MyCustomProvider::class, $provider);
        $this->assertSame([1, 923], $provider->getValue([]));
    }

    public function test_it_throws_on_multiple_value_provider_attributes()
    {
        $class = new class {
            #[MyCustomProvider([1, 923])]
            #[StubDefaultValue('whatever')]
            private readonly mixed $foo;
        };
        $prop = $this->getReflectionProperty($class::class, 'foo');

        $subject = $this->newSubject();
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('only one DefaultValueProvider');
        $subject->getDefaultValueProvider($prop);
    }

    public function test_it_returns_null_provider_if_nothing_specified_for_nullable_property()
    {
        $class = new class {
            private ?string $foo;
        };

        $provider = $this->newSubject()->getDefaultValueProvider($this->getReflectionProperty($class::class, 'foo'));
        $this->assertInstanceOf(StubNullValue::class, $provider);
        $this->assertNull($provider->getValue([]));
    }

    private function newSubject(): AttributeOrGuessingDefaultValueConfigurator
    {
        return new AttributeOrGuessingDefaultValueConfigurator();
    }

    private function getReflectionProperty(string $class, string $property): ReflectionProperty
    {
        $refl = new ReflectionClass($class);

        return $refl->getProperty($property);
    }
}

#[Attribute]
class MyCustomProvider implements DefaultStubValueProvider
{
    public function __construct(private readonly array $parts)
    {

    }

    public function getValue(array $specified_values): mixed
    {
        return $this->parts;
    }

}
