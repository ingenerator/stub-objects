<?php

namespace test\unit\DefaultValueGuesser;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubDefaultValue;
use Ingenerator\StubObjects\DefaultValueGuesser\StubbableObjectValueGuesser;
use Random\Randomizer;

class StubbableObjectValueGuesserTest extends BaseDefaultValueProviderGuesserTestCase
{
    public function test_it_guesses_an_empty_object_for_anything_it_thinks_is_stubbable()
    {
        $class = new class {
            private DateTimeImmutable $date_time;
            private Randomizer $randomizer;
            private StubbableObjectValueGuesserTest $stubbable_1;
        };

        $this->assertGuesses(
            [
                // @todo: fix
//                'date_time' => FALSE,
//                'randomizer' => FALSE,
                'stubbable_1' => [StubDefaultValue::class => []],
            ],
            $class::class,
            new StubbableObjectValueGuesser(),
        );

        $this->markTestIncomplete(
            'Need to work out how to identify/whitelist classes we can recursively stub'
        );
    }
}
