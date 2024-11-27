<?php

namespace test\integration;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubDefault\StubDefaultValue;
use Ingenerator\StubObjects\StubObjects;
use PHPUnit\Framework\TestCase;

class SkipConstructorTest extends TestCase
{

    public function test_it_can_stub_classes_that_have_constructor_args()
    {
        $result = $this->newSubject()->stub(MyStubbableClassWithConstructor::class);
        $this->assertEquals(
            [
                'something_upper' => 'UPPER',
                'nullable' => NULL,
                'whenever' => new DateTimeImmutable('2024-02-02 10:30:04'),
                'a_boolean' => FALSE,
            ],
            (array) $result
        );
    }

    private function newSubject(array $stubbable_class_patterns = ['*']): StubObjects
    {
        return new StubObjects(...get_defined_vars());
    }
}


class MyStubbableClassWithConstructor
{
    #[StubDefaultValue('UPPER')]
    public string $something_upper;

    public function __construct(
        public ?string $nullable,
        #[StubDefaultValue('2024-02-02 10:30:04')]
        public DateTimeImmutable $whenever,
        string $something_else,
        // Note, we have to specify a default stub value because the =FALSE in the code is the *constructor param*
        // default, technically it is not a default property value.
        #[StubDefaultValue(FALSE)]
        public bool $a_boolean = FALSE,
    ) {

    }
}
