<?php

namespace test\unit\Guesser\StubDefault;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubDefault\StubDefaultValue;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser\StubDefaultStubbableObjectGuesser;
use Random\Randomizer;

class StubDefaultStubbableObjectGuesserTest extends BaseStubDefaultGuesserTestCase
{
    public function test_it_guesses_an_empty_object_for_anything_it_thinks_is_stubbable()
    {
        $class = new class {
            private DateTimeImmutable $date_time;
            private Randomizer $randomizer;
            private StubDefaultStubbableObjectGuesserTest $stubbable_1;
        };

        $this->assertGuesses(
            [
                // @todo: fix
//                'date_time' => FALSE,
//                'randomizer' => FALSE,
                'stubbable_1' => [StubDefaultValue::class => []],
            ],
            $class::class,
            new StubDefaultStubbableObjectGuesser(),
        );

        $this->markTestIncomplete(
            'Need to work out how to identify/whitelist classes we can recursively stub'
        );
    }
}
