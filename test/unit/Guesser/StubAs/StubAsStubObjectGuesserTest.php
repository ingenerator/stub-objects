<?php

namespace test\unit\Guesser\StubAs;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubAs\StubAsStubObject;
use Ingenerator\StubObjects\Guesser\StubAsGuesser\StubAsStubObjectGuesser;
use test\TestUtils;

class StubAsStubObjectGuesserTest extends BaseStubAsGuesserTestCase
{
    public function test_it_guesses_stub_as_object_for_stubbable_object()
    {
        $class = new class {
            private string $string;
            private DateTimeImmutable $date;
            private ?MyChildObject $nullable_child;
            private MyChildObject $non_null_child;
            private MyOtherObject $other_object;
        };

        $this->assertGuesses(
            [
                'string' => FALSE,
                'date' => FALSE,
                'nullable_child' => StubAsStubObject::class,
                'non_null_child' => StubAsStubObject::class,
                'other_object' => FALSE,
            ],
            $class::class,
            new StubAsStubObjectGuesser(),
            TestUtils::fakeStubbingContext(stubbable_class_patterns: [MyChildObject::class])
        );
    }

}


class MyChildObject
{

}

class MyOtherObject
{

}
