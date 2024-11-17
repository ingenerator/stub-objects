<?php

namespace test;

use Ingenerator\StubObjects\StubbingContext;
use Ingenerator\StubObjects\StubObjects;

class TestUtils
{

    public static function fakeStubbingContext(
        ?StubObjects $stub_objects = NULL,
        array $stubbable_class_patterns = ['*'],
    ): StubbingContext {
        $stub_objects ??= new class extends StubObjects {
            public function __construct() { }
        };

        return new StubbingContext(
            ...get_defined_vars()
        );
    }
}
