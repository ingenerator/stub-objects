<?php
declare(strict_types=1);

namespace test\unit\Ingenerator\StubObjects\Configurator;


use Attribute;
use DomainException;
use Ingenerator\StubObjects\Attribute\DefaultStubValueProvider;
use Ingenerator\StubObjects\Attribute\StubDefaultValue;
use Ingenerator\StubObjects\Attribute\StubNullValue;
use Ingenerator\StubObjects\Configurator\AttributeOrGuessingDefaultValueConfigurator;
use Ingenerator\StubObjects\DefaultValueGuesser\DefaultValueProviderGuesser;
use Ingenerator\StubObjects\DefaultValueGuesser\StubNullValueGuesser;
use PHPUnit\Framework\Attributes\TestWith;
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

    #[TestWith(['foo', NULL])]
    #[TestWith(['bar', 'whatever'])]
    public function test_it_returns_guessed_provider_if_any(string $property, ?string $expect_value)
    {
        $guessers = [
            new class implements DefaultValueProviderGuesser {
                public function guessProvider(ReflectionProperty $property): false|DefaultStubValueProvider
                {
                    return match ($property->getName() === 'foo') {
                        FALSE => FALSE,
                        TRUE => new StubNullValue(),
                    };
                }
            },
            new class implements DefaultValueProviderGuesser {
                public function guessProvider(ReflectionProperty $property): false|DefaultStubValueProvider
                {
                    return match ($property->getName() === 'bar') {
                        FALSE => FALSE,
                        TRUE => new StubDefaultValue('whatever'),
                    };
                }
            },
        ];
        $class = new class {
            private ?string $foo;
            private string $bar;
        };
        $provider = $this
            ->newSubject(guessers: $guessers)
            ->getDefaultValueProvider($this->getReflectionProperty($class::class, $property));

        $this->assertInstanceOf(DefaultStubValueProvider::class, $provider);
        $this->assertSame($expect_value, $provider->getValue([]));
    }

    public function test_it_throws_if_no_guesser_can_find_a_default_provider()
    {
        $subject = $this->newSubject(guessers: [new StubNullValueGuesser()]);
        $class = new class {
            private string $foo;
        };
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Could not guess a default value for `string $foo`');
        $subject->getDefaultValueProvider($this->getReflectionProperty($class::class, 'foo'));
    }

    private function newSubject(array $guessers = []): AttributeOrGuessingDefaultValueConfigurator
    {
        $guessers ??= [
            new StubNullValueGuesser(),
        ];

        return new AttributeOrGuessingDefaultValueConfigurator($guessers);
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
