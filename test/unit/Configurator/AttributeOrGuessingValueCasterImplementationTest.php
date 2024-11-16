<?php
declare(strict_types=1);

namespace test\unit\Configurator;

use Attribute;
use DateTimeImmutable;
use DomainException;
use Ingenerator\StubObjects\Attribute\Caster\StubAsDateTime;
use Ingenerator\StubObjects\Attribute\StubValueCaster;
use Ingenerator\StubObjects\Configurator\AttributeOrGuessingValueCasterConfigurator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;

class AttributeOrGuessingValueCasterImplementationTest extends TestCase
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

    public function test_it_returns_first_guessed_caster_if_untagged()
    {
        $this->markTestIncomplete();
    }

    public function test_it_returns_false_if_no_caster_found()
    {
        $this->markTestIncomplete();
    }

    private function newSubject(array $guessers = []): AttributeOrGuessingValueCasterConfigurator
    {
        return new AttributeOrGuessingValueCasterConfigurator($guessers);
    }

    private function getReflectionProperty(string $class, string $property): ReflectionProperty
    {
        $refl = new ReflectionClass($class);

        return $refl->getProperty($property);
    }

}

#[Attribute(Attribute::TARGET_PROPERTY)]
class MyReversingCaster implements StubValueCaster
{
    public function cast(string $property, mixed $value): mixed
    {
        return strrev($value);
    }
}
