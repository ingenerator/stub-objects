<?php
declare(strict_types=1);

namespace test\unit;

use Ingenerator\StubObjects\FailedToStubObjectException;
use Ingenerator\StubObjects\StubObjectFactory;
use PHPUnit\Framework\TestCase;

class StubObjectFactoryTest extends TestCase
{
    public function test_it_can_create_instance_of_empty_class(): void
    {
        $class = new class {
        };

        $instance = $this->newSubject()->stub($class::class, []);

        $this->assertInstanceOf($class::class, $instance);
        $this->assertSame([], (array) $instance);
    }

    public function test_it_throws_if_class_does_not_exist(): void
    {
        $class = '\Some\Class\That\Must\NotExist'.uniqid();
        $this->assertFalse(class_exists($class), 'Did not expect '.$class.' to exist');
        $subject = $this->newSubject();

        $this->expectException(FailedToStubObjectException::class);
        $this->expectExceptionMessage('Could not stub a '.$class);
        $subject->stub($class);
    }

    private function newSubject(): StubObjectFactory
    {
        return new StubObjectFactory();
    }

}
