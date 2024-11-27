<?php

namespace test\integration;

use Ingenerator\StubObjects\Attribute\StubDefault\StubDefaultValue;
use Ingenerator\StubObjects\StubObjects;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class HandlesUnsupportedTypesTest extends TestCase
{

    #[TestWith([[], ['something' => 'default']])]
    #[TestWith([['something' => 'whatever'], ['something' => 'whatever']])]
    public function test_it_can_stub_objects_with_union_types(array $data, array $expect)
    {
        $class = new class {
            #[StubDefaultValue('default')]
            public bool|string $something;
        };

        $result = $this->newSubject()->stub($class::class, $data);

        $this->assertSame($expect, (array) $result);
    }

    #[TestWith([[], ['something' => null]])]
    #[TestWith([['something' => 'whatever'], ['something' => 'whatever']])]
    public function test_it_can_stub_objects_with_untyped_properties(array $data, array $expect)
    {
        $class = new class {
            // Note: this will be auto-detected as able to be stubbed with `null`, which is technically correct.
            public $something;
        };

        $result = $this->newSubject()->stub($class::class, $data);

        $this->assertSame($expect, (array) $result);
    }


    private function newSubject(array $stubbable_class_patterns = ['*']): StubObjects
    {
        return new StubObjects(...get_defined_vars());
    }
}
