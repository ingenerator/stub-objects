<?php

namespace test\integration;

use Ingenerator\StubObjects\Attribute\StubDefault\StubDefaultValue;
use Ingenerator\StubObjects\StubObjects;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RecursiveStubbingTest extends TestCase
{

    public static function provider_recursive(): array
    {
        return [
            'nothing specified, use defaults' => [
                [],
                ['parent_name' => 'Brian', 'child' => ['name' => 'my child name', 'nickname' => NULL]],
            ],
            'customise parent' => [
                ['parent_name' => 'Andrew'],
                ['parent_name' => 'Andrew', 'child' => ['name' => 'my child name', 'nickname' => NULL]],
            ],
            'partial customise child' => [
                ['child' => ['nickname' => 'Rizzler']],
                ['parent_name' => 'Brian', 'child' => ['name' => 'my child name', 'nickname' => 'Rizzler']],
            ],
            'customise everything' => [
                ['parent_name' => 'Phil', 'child' => ['name' => 'Robert', 'nickname' => 'Bobby']],
                ['parent_name' => 'Phil', 'child' => ['name' => 'Robert', 'nickname' => 'Bobby']],
            ],
        ];
    }

    #[DataProvider('provider_recursive')]
    public function test_it_can_recursively_stub_objects(array $values, array $expect)
    {
        $class = new class {
            #[StubDefaultValue('Brian')]
            private string $parent_name;

            private RecursiveStubbingChildObject $child;

            public function getValues(): array
            {
                $vars = get_object_vars($this);
                $vars['child'] = $this->child->getValues();

                return $vars;
            }
        };

        $result = $this->newSubject()->stub($class::class, $values);
        $this->assertInstanceOf($class::class, $result);
        $this->assertSame($expect, $result->getValues());
    }

    private function newSubject(array $stubbable_class_patterns = ['*']): StubObjects
    {
        return new StubObjects(...get_defined_vars());
    }
}

readonly class RecursiveStubbingChildObject
{
    #[StubDefaultValue('my child name')]
    private string $name;

    private ?string $nickname;

    public function getValues(): array
    {
        return get_object_vars($this);
    }
}
