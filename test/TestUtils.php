<?php

namespace test;

use Closure;
use Ingenerator\StubObjects\StubbingContext;
use Ingenerator\StubObjects\StubObjects;
use RuntimeException;

class TestUtils
{

    public static function fakeStubObjects(Closure $stub): StubObjects
    {
        return new class($stub) extends StubObjects {
            public function __construct(private readonly Closure $stub_callback) { }

            public function stub(string $class, array $values = []): object
            {
                return ($this->stub_callback)($class, $values);
            }
        };
    }

    public static function fakeStubbingContext(
        ?StubObjects $stub_objects = NULL,
        array $stubbable_class_patterns = ['*'],
    ): StubbingContext {
        $stub_objects ??= self::fakeStubObjects(fn() => throw new RuntimeException('Faked StubObjects not defined'));

        return new StubbingContext(
            ...get_defined_vars()
        );
    }
}
