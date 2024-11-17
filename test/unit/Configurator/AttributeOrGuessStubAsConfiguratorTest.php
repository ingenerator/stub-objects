<?php
declare(strict_types=1);

namespace test\unit\Configurator;

use Attribute;
use DateTimeImmutable;
use DomainException;
use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\Attribute\StubAs\StubAsDateTime;
use Ingenerator\StubObjects\Configurator\AttributeOrGuessStubAsConfigurator;
use Ingenerator\StubObjects\Guesser\StubAsGuesser;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;

class AttributeOrGuessStubAsConfiguratorTest extends TestCase
{

    public function test_it_casts_with_predefined_caster_attribute()
    {
        $class = new class {
            #[StubAsDateTime]
            public mixed $foo;
        };

        $provider = $this->newSubject()->getCaster($this->getReflectionProperty($class::class, 'foo'));
        $this->assertInstanceOf(StubAsDateTime::class, $provider);
        $this->assertEquals(new DateTimeImmutable('2024-02-01 00:00:00'), $provider->cast('foo', '2024-02-01'));
    }

    public function test_it_returns_custom_hydrator_attribute()
    {
        $class = new class {
            #[MyReversingCaster]
            private readonly mixed $foo;
        };
        $provider = $this->newSubject()->getCaster($this->getReflectionProperty($class::class, 'foo'));
        $this->assertInstanceOf(MyReversingCaster::class, $provider);
        $this->assertSame('emases', $provider->cast('foo', 'sesame'));
    }

    public function test_it_throws_on_multiple_caster_attributes()
    {
        $class = new class {
            #[StubAsDateTime]
            #[MyReversingCaster]
            private readonly mixed $foo;
        };
        $prop = $this->getReflectionProperty($class::class, 'foo');

        $subject = $this->newSubject();
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('only one StubValueCaster');
        $subject->getCaster($prop, 'abc');
    }

    #[TestWith(['foo', StubAsDateTime::class])]
    #[TestWith(['bar', FALSE])]
    public function test_it_returns_first_guessed_caster_or_false_if_not_tagged(string $property, false|string $expect_caster)
    {
        $guessers = [
            new class implements StubAsGuesser {
                public function guessCaster(ReflectionProperty $property): false|StubAs
                {
                    return match ($property->getName() === 'foo') {
                        FALSE => FALSE,
                        TRUE => new StubAsDateTime()
                    };
                }
            },
        ];
        $class = new class {
            private DateTimeImmutable $foo;
            private string $bar;
        };
        $provider = $this
            ->newSubject(guessers: $guessers)
            ->getCaster($this->getReflectionProperty($class::class, $property));

        if ($expect_caster === FALSE) {
            $this->assertFalse($provider);
        } else {
            $this->assertInstanceOf($expect_caster, $provider);
        }
    }

    private function newSubject(array $guessers = []): AttributeOrGuessStubAsConfigurator
    {
        return new AttributeOrGuessStubAsConfigurator($guessers);
    }

    private function getReflectionProperty(string $class, string $property): ReflectionProperty
    {
        $refl = new ReflectionClass($class);

        return $refl->getProperty($property);
    }

}

#[Attribute(Attribute::TARGET_PROPERTY)]
class MyReversingCaster implements StubAs
{
    public function cast(string $property, mixed $value): mixed
    {
        return strrev($value);
    }
}
