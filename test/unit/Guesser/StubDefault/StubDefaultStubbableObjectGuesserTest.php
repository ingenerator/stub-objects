<?php

namespace test\unit\Guesser\StubDefault;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Ingenerator\StubObjects\Attribute\StubDefault\StubDefaultValue;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser\StubDefaultStubbableObjectGuesser;
use Random\Randomizer;
use test\TestUtils;

class StubDefaultStubbableObjectGuesserTest extends BaseStubDefaultGuesserTestCase
{
    public function test_it_guesses_an_empty_object_for_anything_it_thinks_is_stubbable()
    {
        $class = new class {
            private DateTimeImmutable $date_time;
            private Randomizer $randomizer;
            private MyChildStubbable $not_null_stubbable;

            private Collection $collection_stubbable;

        };

        $this->assertGuesses(
            [
                'date_time' => FALSE,
                'randomizer' => FALSE,
                'not_null_stubbable' => [StubDefaultValue::class => []],
                'collection_stubbable' => [StubDefaultValue::class => []],
            ],
            $class::class,
            new StubDefaultStubbableObjectGuesser(),
            TestUtils::fakeStubbingContext(
                stubbable_class_patterns: [MyChildStubbable::class],
            )
        );
    }
}

class MyChildStubbable
{
}
